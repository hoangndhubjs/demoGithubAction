<?php
namespace App\Http\Controllers\Admin\TimeSheet;

use App\Http\Controllers\Controller;
use App\Models\AttendanceDaily;
use App\Models\Company;
use App\Models\Department;
use App\Models\Employee;
use App\Models\EmployeeTmpPayslip;
use App\Models\Holiday;
use App\Models\Leave;
use App\Models\LeaveApplication;
use App\Repositories\DashboardRepository;
use App\Repositories\EmployeeRepository;
use App\Repositories\EmployeesPheptonRepository;
use App\Repositories\EmployeeTmpPayslipRepository;
use App\Repositories\HolidayRepository;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use App\Traits\DatatableResponseable;
use App\Classes\PaginateConfig;
use App\Repositories\AttendanceRepository;
use App\Repositories\AttendanceDailyRepository;
use Illuminate\Support\Facades\Auth;
use Carbon\CarbonPeriod;
use Moment\Moment;
use App\Repositories\AttendanceTimeRequestRepository;

class TimeSheetController extends Controller
{
    use DatatableResponseable;
    private $attendance;
    private $attendance_monthly;
    private $employees_tmp_payslip;
    private $employees;
    private $attendace_time_request;

    public function __construct(
        AttendanceRepository $attendance,
        AttendanceDailyRepository $attendance_monthly,
        EmployeeTmpPayslipRepository $employees_tmp_payslip,
        EmployeeRepository $employees,
        AttendanceTimeRequestRepository $attendace_time_request
    ) {
        $this->attendance = $attendance;
        $this->attendance_monthly = $attendance_monthly;
        $this->employees_tmp_payslip = $employees_tmp_payslip;
        $this->employees = $employees;
        $this->attendace_time_request = $attendace_time_request;
    }

    public function attendance() {
        $page_title = __("dashboard_attendance");
        return view('pages.timesheet.attendance', compact('page_title'));
    }

    public function attendance_monthly(Request $request) {
        $id = $request->id;
        $month = $request->month;
        $employee = (new EmployeeRepository())->find($id);
        $page_title = __("dashboard_attendance");
        return view('admin.timesheet.attendance_monthly', compact('page_title', 'id', 'month', 'employee'));
    }

    public function listAttendance(Request $request) {
        $paginateConfig = PaginateConfig::fromDatatable($request);
        $startDate = $request->get('attendance_start_date') ?? date("Y-m-d");
        $endDate = $request->get("attendance_end_date") ?? date("Y-m-d");
        $userId = Auth::id();
        $attendances = $this->attendance->getAttendancesOn($paginateConfig, $userId, $startDate, $endDate);
        return $this->makeDatatableResponse($attendances, $paginateConfig->getSortColumn(), $paginateConfig->getSortDir());
    }

    public function listAttendanceMonthly(Request $request) {
        $startDate = $request->get('start_date')??$request->month.'-01';
        $endDate = $request->get("end_date")??$request->month."-".date('t', strtotime($startDate));
        $userId = $request->id;
        $attendances = $this->attendance_monthly->getAttendancesDailyOn($userId, $startDate, $endDate)->keyBy('day');
        $dates = CarbonPeriod::create($startDate, $endDate);
        $events = collect();
        foreach($dates as $date) {
            if ($date->diffInDays(now(), false) < 1) {
                break;
            }
            $dateStr = $date->format('Y-m-d');
            $attendance = $attendances->get($dateStr);
            if ($attendance) {
                list($class, $label) = $this->getAttendanceColor($attendance, $date);
                $type = $attendance->is_basic ? '1' : '2';
                $eventLabel = $label;
                if (!($eventLabel == '' || $eventLabel == 'P')) {
                    $eventLabel .= $type;
                }
                $event = array(
                    'start' => $dateStr,
                    'className' => 'fc-attendance-event',
                    'rendering' => 'background',
                    'attendance' => $attendance,
                    'class' => $class,
                    'is_sunday' => ($date->dayOfWeek === 0),
                    'event_label' => $eventLabel
                );
                $events->put($dateStr, $event);
            }
        }
        return $this->responseSuccess($events);
    }

    protected function getAttendanceColor($attendance, $date) {
        $colors = array(
            'full' => 'attendance-full',
            'half' => 'attendance-half',
            'holiday' => 'attendance-holiday',
            'leave_requested' => 'attendance-leave-requested',
            'leave' => 'attendance-leave',
            'sunday' => 'attendance-sunday',
            'businessGoOn' => 'attendance-go-on',
        );
        $labels = array(
            'full' => 'X',
            'half' => 'N',
            'holiday' => 'L',
            'leave_requested' => 'P',
            'leave' => 'V',
            'businessGoOn' => 'C',
            'sunday' => ''
        );
        $color = $colors['leave'];
        $label = $labels['leave'];
        if (!$attendance) {
            return $color;
        } else {
            if ($attendance->is_go_on_business) {
                $color = $colors['businessGoOn'];
                $label = $labels['businessGoOn'];
            } else if ($attendance->is_holiday) {
                $color = $colors['holiday'];
                $label = $labels['holiday'];
            } else if($date->dayOfWeek === 0) {
                $color = $colors['sunday'];
                $label = $labels['sunday'];
            } else if ($attendance->is_leave_with_request) {
                $color = $colors['leave_requested'];
                $label = $labels['leave_requested'];
            } else if($attendance->is_full_attendance) {
                $color = $colors['full'];
                $label = $labels['full'];
            } else if ($attendance->is_half_attendance) {
                $color = $colors['half'];
                $label = $labels['half'];
            }
            return array($color, $label);
        }
        return array($colors['full'], $labels['full']);
    }

    public function getTotalWorkingDays($month) {
        $startDate = $month."-01";
        $endDate = $month."-".date('t', strtotime($startDate));
        $period = CarbonPeriod::create($startDate, $endDate);
        $attendance = 0;
        foreach($period as $date) {
            if (!$date->isSunday()) { $attendance++; }
        }
        return $attendance;
    }

    public function attendanceMonthlySummary(Request $request) {
        $month = $request->get('month');
        $startDate = $month."-01";
        $endDate = $month."-".date('t', strtotime($startDate));
        $userId = $request->id;
        $summary = $this->attendance_monthly->getSummaryAttendancesDailyOn($userId, $startDate, $endDate);
        $totalWorkingDays = $this->getTotalWorkingDays($month);
        $totalPhepTon = (new EmployeesPheptonRepository())->checkPhepTon(['employee_id'=>Auth::id(), 'leave_type_id'=>Leave::LEAVE_TYPE_PHEP_TON, 'year'=>date('Y')]);
        //Mapping data;
        $data = array(
            'total_late' => $summary->total_late_minutes.'/'.config('constants.MAXIMUM_LATE_TIME_ALLOWED'),
            'total_late_color' => $this->getAttendanceCardColor('late', $summary->total_late_minutes, config('constants.MAXIMUM_LATE_TIME_ALLOWED')),
            'total_early_leave' => $summary->total_early,
            'total_early_leave_color' => $this->getAttendanceCardColor('early', $summary->total_early, 0),
            'total_attendance' => $summary->total_attendance.'/'.$totalWorkingDays,
            'total_attendance_color' => $this->getAttendanceCardColor('attendance', $summary->total_attendance, 0),
            'total_leave_day' => $summary->total_leave,
            'total_leave_day_color' => $this->getAttendanceCardColor('leave', $summary->total_leave, 0),
            'total_overtime' => $summary->total_overtime,
            'total_phep_ton' => (isset($totalPhepTon))?$totalPhepTon->remain_of_number.'/'.$totalPhepTon->grant_of_number:'0/0',
            'total_phep_ton_color' => $this->getAttendanceCardColor('phep_ton', $totalPhepTon?$totalPhepTon->remain_of_number:0, $totalPhepTon?$totalPhepTon->grant_of_number:0),
        );
        return $this->responseSuccess($data);
    }

    public function getAttendanceCardColor($type, $number, $max) {
        $colors = [
            'default' => 'attendance-full',
            'warning' => 'attendance-leave-requested',
            'danger'  => 'attendance-leave'
        ];
        $color = $colors['default'];
        if ($max === 0) {
            return $color;
        }
        $percent = $number/$max;
        $color_key = $percent > 1 ? 'danger' : ($percent === 1 ? 'warning' : 'default');
        return $colors[$color_key] ?? $color;
    }

    public function list(){
        $page_title = __("attendance_monthly_list");
        return view('admin.timesheet.timesheet_monthly', compact('page_title'));
    }

    public function timesheetByMonth(Request $request){
        $paginateConfig = PaginateConfig::fromDatatable($request);
        $attendances = $this->employees_tmp_payslip->getTimeSheetByMonth($paginateConfig, $request);
        return $this->makeDatatableResponse($attendances, $paginateConfig->getSortColumn(), $paginateConfig->getSortDir());
    }

    public function monthlyTimesheet(Request $request){

        $page_title = __("attendance_monthly_list");

        $defaultDate = $request->month ? date('Y-m', strtotime('01-'.$request->month)) : date('Y-m');
        $user_id = $request->user_id;
        $department_id = $request->department_id;
        if ($department_id) {
            $employeeIsActive = $this->department($department_id);
        } else {
            $employeeIsActive = $this->employees->getEmployeesByCompany(Auth::user()->company_id);
        }
        list($resources, $events) = $this->fullcalendarByMonth($user_id, $defaultDate, $department_id);

        $allDeparment = Department::where('company_id', Auth::user()->company_id)->get();

        return view('admin.timesheet.monthly_timesheet', compact('page_title', 'resources', 'events', 'employeeIsActive', 'defaultDate', 'user_id', 'allDeparment', 'department_id'));

    }

    public function fullcalendarByMonth ($user_id, $getDate, $department_id) {

        $date = $getDate .'-01';
        $imonth_year = explode('-',$getDate);
        $day = date('d', strtotime($date));
        $month = date($imonth_year[1], strtotime($date));
        $year = date($imonth_year[0], strtotime($date));
        $month_year = $getDate;

        // total days in month
        $date = DateTime::createFromFormat('Y-m', $getDate);
        $daysInMonth = $date->format('t');

        if ($user_id == null && $department_id == null) {
            $employeeIsActive = $this->employees->getEmployeesByCompany(Auth::user()->company_id);
        } else {
            $employeeIsActive = $this->employees->getUserIdByCompany($user_id, Auth::user()->company_id, $department_id);
        }

        $payslip =  $this->employees_tmp_payslip->getInformationTimeSheetByMonth($getDate, $employeeIsActive);

        $dataPayslip = [];
        foreach ($payslip as $key => $payslipID) {

            for($i = 1; $i <= $daysInMonth; $i++){
                $i = str_pad($i, 2, 0, STR_PAD_LEFT);
                $attendance_date = $year.'-'.$month.'-'.$i;
                $date_ = Carbon::createFromDate($attendance_date);
                $daily =  app()->make(AttendanceDaily::class)
                    ->with(['employee.office_shift'])
                    ->where('employee_id', $payslipID['id'])
                    ->where('day', $attendance_date)
                    ->first();
//                dump($daily, $attendance_date, ($date_->englishDayOfWeek === 'Sunday' && $sunday_in_time == ""));
                $status = '';
                if ($daily == null) {
                    if ($date_->englishDayOfWeek  === 'Sunday') {
                        $status = 'CN';
                    } else {
                        $status = '--';
                    }
                } else {
                    $type = $daily->wages_type == 1 ? '1' : '2';
                    if (isset($daily->is_go_on_business) && $daily->is_go_on_business == 1) {
                        $status = 'C'.$type;
                    } else {
                        $office_shift = $daily->employee->office_shift ?? null;
                        $sunday_in_time = $office_shift->sunday_in_time ?? null;

                        if ($daily->is_holiday == 1) {
                            $status = 'L' . $type;
                        } else if ($daily->is_online == 1) {
                            $status = 'O' . $type;
                        } else if ($daily->is_half_attendance == 1) {
                            $status = 'N' . $type;
                        } else if ($daily->total_request_leave_full == 1) {
                            $status = 'P';
                        } else if ($daily->attendance_count == 1) {
                            $status = 'X' . $type;
                        } else if ($date_->englishDayOfWeek === 'Sunday' && $sunday_in_time != "" ) {
                            //Thang phai lam chu nhat
                            if ($daily->is_holiday == 1) {
                                $status = 'L' . $type;
                            } else if ($daily->is_online == 1) {
                                $status = 'O' . $type;
                            } else if ($daily->is_half_attendance == 1) {
                                $status = 'N' . $type;
                            } else if ($daily->total_request_leave_full == 1) {
                                $status = 'P';
                            } else if ($daily->attendance_count == 1) {
                                $status = 'X' . $type;
                            } else if ($daily->attendance_count == 0 || ($daily->check_in_at == null && $daily->check_out_at == null)) {
                                $status = 'K';
                            }
                        } else if ($date_->englishDayOfWeek === 'Sunday' && $sunday_in_time == "") {
                            //Thang ko phai lam chu nhat;
                            $status = "CN";
                        } else if ($daily->attendance_count == 0 || ($daily->check_in_at == null && $daily->check_out_at == null)) {
                            $status = 'K';
                        }
                    }
                }

                $dataPayslip[] = [
                    'resourceId' => $payslipID['id'],
                    'title' => $status,
                    'start' => $attendance_date,
                    'end' => $attendance_date,
                    'className' => $status == "P/2" ? "P_2" : $status
                ];

            }

        }

        return [$payslip, $dataPayslip];
    }

    public function attendanceTime(){
        $page_title = __("attendance_daily_list");
        return view('admin.timesheet.attendance_time', compact('page_title'));
    }

    public function timesheetByDay(Request $request){
        $paginateConfig = PaginateConfig::fromDatatable($request);
        $attendances = $this->attendance->getTimeSheetByDay($paginateConfig, $request);
        return $this->makeDatatableResponse($attendances, $paginateConfig->getSortColumn(), $paginateConfig->getSortDir());
    }

    public function attendancTimeByEmployee(Request $request){
        $id = $request->id;
        $date = $request->date;
        $listEmployee = (new EmployeeRepository())->getNameEmployeesByCompany(Auth::user()->company_id);
        $page_title = __("attendance_daily_list");
        return view('admin.timesheet.attendance_time_employee', compact('page_title', 'id', 'date', 'listEmployee'));
    }

    public function timesheetByEmloyee(Request $request){
        $paginateConfig = PaginateConfig::fromDatatable($request);
        $attendances = $this->attendance->getTimeSheetByEmployee($paginateConfig, $request);
        return $this->makeDatatableResponse($attendances, $paginateConfig->getSortColumn(), $paginateConfig->getSortDir());
    }

    public function workingTime() {

        $employeeActive = $this->employees->getEmployeesByCompany(1);
        $totalEmployeeLate = $this->employees_tmp_payslip->getTotalEmployee();
        $leaveApplicationByMonth = $this->employees_tmp_payslip->leaveApplicationByMonth();
        $totalEmployee = count($employeeActive);
        $employeeLateMost = $this->employees_tmp_payslip->employeeLateMost($employeeActive);
        $totalQuitWorkMost = $this->employees_tmp_payslip->totalQuitWorkMost($employeeActive);

        return view('admin.working-time.working-time', compact('totalEmployee', 'totalEmployeeLate', 'leaveApplicationByMonth', 'employeeLateMost', 'totalQuitWorkMost'));
    }

    public function getDepartmentId(Request $request) {
        $department_id = $this->department($request->id);

        return response()->json($department_id);
    }

    public function department($department_id) {
        $is_active = 1;
        $query = Employee::where('company_id', Auth::user()->company_id)->where('is_active', $is_active);

        if ($department_id) {
            $query->where('department_id', $department_id);
        }

        return $query->get();
    }

    /**
     * holidays
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function holiday(){
        $page_title = __("xin_manage_holidays");
        return view('admin.timesheet.holiday', compact('page_title'));
    }

    /**
     * get list holiday
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function listHoliday(Request $request){
        $paginateConfig = PaginateConfig::fromDatatable($request);
        $attendances = (new HolidayRepository())->getHoliday($paginateConfig);
        return $this->makeDatatableResponse($attendances, $paginateConfig->getSortColumn(), $paginateConfig->getSortDir());
    }

    /**
     * remove holiday
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteHoliday(Request $request){
        if (!$id = $request->get('id')) {
            return $this->responseError(__("nothing_to_delete"));
        }
        if ((new HolidayRepository())->delete($id)) {
            return $this->responseSuccess(__("delete_success"));
        }
        return $this->responseError(__("delete_fail"));
    }

    public function createHolidayForm(Request $request){
        $id = $request->get('id', null);
        $status = Holiday::STATUS_LABELS;
        $holiday = null;
        if($id){
            $type = 'updated';
            $holiday = (new HolidayRepository())->find($id);
        } else {
            $type = 'created';
        }
        return view('admin.timesheet.holiday_form_modal', compact('holiday', 'type', 'status'));
    }

    public function holidayStore(Request $request){
        if(
            (strtotime($request->start_date) > strtotime($request->end_date))
        ){
            return $this->responseError( __('xin_error_start_end_date'));
        }

        $data = array(
            'company_id' => Auth::user()->company_id,
            'event_name' => $request->event_name,
            'start_date' => ($request->start_date)?date('Y-m-d', strtotime($request->start_date)):'',
            'end_date' => ($request->end_date)?date('Y-m-d', strtotime($request->end_date)):'',
            'description' => $request->description,
            'is_publish' => $request->is_publish
        );
        // kiểm tra request trùng

        if ($id = $request->get('id')) {
            $holiday = (new HolidayRepository())->update($id, $data);
        } else {
            $holiday = (new HolidayRepository())->create($data);
        }
        return $this->responseSuccess($holiday);
    }

    // OT month
    public function overtime_month(Request $request){

        $page_title = __("attendance_monthly_ot");

        $defaultDate = $request->month ? date('Y-m', strtotime('01-'.$request->month)) : date('Y-m');
        $user_id = $request->employee_name;
        $employee_name = $request->employee_name;
        $department_id = $request->department_id;
        if ($department_id) {
            $employeeIsActive = $this->department($department_id);
        } else {
            $employeeIsActive = $this->employees->getEmployeesByCompany(Auth::user()->company_id);
        }

        $getTimeRequest = $this->attendace_time_request->getTimeRequest($request);
//        dd($getTimeRequest->toArray());
        $user_request_time = array();
        $total_hour = array();

        $total_hour_count = 0;
        foreach ($getTimeRequest as $key => $item){
            if($item->employee->department_id == $department_id || $department_id === null){
                $user_request_time[$item->employee_id][] = array(
                    'id' => $item->employee_id,
                    'employee_name' => $item->employee->last_name.' '.$item->employee->first_name,
                    'employee_id' => $item->employee_id,
                    'date' => $item->request_date,
                    'department' => $item->employee->department->department_name,
                    'total_hour_user' => $item->total_hour_minutes,
                    'total_hour_tw_user' => $item->total_tw_hour_minutes
                );
            }

            $total_hour[] = array(
                "resourceId" => $item->employee_id,
                "title" => ((double)$item->total_hour_minutes + (double)$item->total_tw_hour_minutes )/ 60,
                "start" => $item->request_date,
                "end" => $item->request_date,
                "className" => "time_request",
            );

        }
        $total_hour_count = [];
        foreach ($user_request_time as $key2 => $value){
            $map_data = array_map(function ($data){
               return $data['total_hour_user'];
            }, $value);
            $map_tw_data = array_map(function ($data_tw){
               return $data_tw['total_hour_tw_user'];
            }, $value);
            $total_hour_count[] = array(
                'id' => $value[0]['employee_id'],
                'employee_name' => $value[0]['employee_name'],
                'employee_id' => $value[0]['employee_id'],
                'department' => $value[0]['department'],
                'total_hour_user' => array_sum($map_data) / 60,
                'total_hour_tw_user' => array_sum($map_tw_data) / 60,
            );

        }

        $allDeparment = Department::where('company_id', Auth::user()->company_id)->get();

        return view('admin.timesheet.OT_Timesheet.overtime_month',
            compact('page_title', 'total_hour_count',
                'total_hour', 'employeeIsActive', 'defaultDate',
                'allDeparment', 'department_id',
            'employee_name'
            ));

    }

}
