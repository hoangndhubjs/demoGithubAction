<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Imports\AssertImport;
use App\Models\Department;
use App\Models\SalaryPayslip;
use App\Repositories\DashboardRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Meeting;
use App\Models\Employee;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Excel;
use App\Repositories\AttendanceTimeRepository;
class DashboardController extends Controller
{
    private $dashboard;
    private $attendance;

    public function __construct(DashboardRepository $dashboard, AttendanceTimeRepository $attendance)
    {
        $this->dashboard = $dashboard;
        $this->attendance = $attendance;
    }

    public function index()
    {
        return Auth::user()->isAdmin() ? $this->admin() : $this->user() ;
    }

    public function user() {
        $page_title = __('dashboard_title');

        $greeting = $this->dashboard->getGreetingByTime();
        $order_meal = $this->dashboard->countOrderMeal();
        $min_late_soon = $this->dashboard->getMinuteLateLeaveEarly();
        $attendance = $this->dashboard->getTotalWorkingDayInMonth();
        $day_off = $this->dashboard->getTotalDayOffInMonth();
        $workday_to_do = $this->dashboard->getTotaWorkdayToDoInMonth();

        return view('pages.dashboard.index', compact('page_title', 'greeting', 'order_meal', 'min_late_soon', 'attendance', 'day_off', 'workday_to_do'));
    }

    public function admin() {
        $page_title = __('dashboard_title');
        $leave = $this->dashboard->getEmployeeExitToday();
        $totalEmployee = $this->dashboard->getEmployeeIsActive();
        $working = $totalEmployee - $leave;
        $payrollByMonth =  $this->dashboard->payrollByMonth();
        $payrollByDepartment =  $this->dashboard->payrollByDepartment();
        $employeeDepartment = $this->dashboard->employeeActiveDepartment();
        $totalEmployeeDepartment = array_sum($employeeDepartment[1]);

        return view('pages.dashboard.dashboard_admin', compact('page_title', 'leave', 'totalEmployee', 'working', 'payrollByDepartment', 'employeeDepartment', 'totalEmployeeDepartment', 'payrollByMonth'));
    }

    public function event(Request $request)
    {
        $yMD = $request->yMD;
        $mD = $request->mD;
        $data = [];

        $data['meeting'] = Meeting::where('meeting_date', $yMD)->get();
        $data['birthday'] = Employee::where('date_of_birth', 'like', '%' . $mD)->where('is_active', 1)->get();

        return response()->json($data);
    }

    public function quote()
    {
        $get_data_quotes = file_get_contents(base_path('resources/views/pages/dashboard/quotes.json'));

        $data = json_decode($get_data_quotes, true);

        $random = $data['quoctes_covid'][array_rand($data['quoctes_covid'])];

        return response()->json($random);
    }


    public function import() {
        $file = base_path('asset.xlsx');
        \Maatwebsite\Excel\Facades\Excel::import(new AssertImport(), $file);
    }
    /**
     * Điểm danh học sinh đến lớp v( ^_^ )v
     */
    public function attendanceClasses(Request $request){
        if (!Auth::id()){
            return redirect("/logout");
        }
//        dd($request->all());
        $employee_id = Auth::id();
        $employee_info = Employee::find($employee_id);
        $clock_state = $request->dataType;
        $latitude = $request->latitude;
        $longitude = $request->longitude;
        // set time
        $nowTime = Carbon::now();

        $nowtime = $nowTime->format("Y-m-d H:i:s");
        //$date = date('Y-m-d H:i:s', strtotime($nowtime . ' + 4 hours'));
        $date = $nowTime->format("Y-m-d H:i:s");
        $curtime = $date;
        $today_date = $nowTime;
        if($clock_state == 'check_in' || $clock_state == 'check_out') {
            $query = $this->attendance->check_user_attendance();
            if(!$query) {
                $total_rest = '';
                if($clock_state && $clock_state == 'check_out') return '';
            } else {
                $timeCheckin = Carbon::createFromFormat("Y-m-d H:i:s", $query->clock_in);
                $cin = Carbon::createFromFormat("Y-m-d H:i:s", $curtime);
//                $cout =  new DateTime($query->clock_out);
//                $cin =  new DateTime($curtime);
                $interval_cin = $cin->diff($timeCheckin);
                $hours_in   = $interval_cin->format('%h');
                $minutes_in = $interval_cin->format('%i');
                $total_rest = $hours_in .":".$minutes_in;
            }
            if($clock_state=='check_out') {
                $data = array(
                    'employee_id' => $employee_id,
                    'clock_out' => $curtime,
                    'clock_out_ip_address' => $request->ip(),
                    'clock_out_latitude' => $latitude,
                    'clock_out_longitude' => $longitude,
                    'clock_in_out' => '0',
                    'early_leaving' => $curtime,
                    'overtime' => $curtime,
                    'total_work' => $total_rest,
                    'is_online' => 1,
                    'wages_type' => $employee_info['wages_type']
                );
                $result = $this->attendance->update($query->time_attendance_id, $data);
            }else{
                $data = array(
                    'employee_id' => $employee_id,
                    'attendance_date' => $today_date->format('Y-m-d'),
                    'clock_in' => $curtime,
                    'clock_in_ip_address' => $request->ip(),
                    'clock_in_latitude' => $latitude,
                    'clock_in_longitude' => $longitude,
                    'time_late' => $curtime,
                    'early_leaving' => $curtime,
                    'overtime' => $curtime,
                    'attendance_status' => 'Present',
                    'clock_in_out' => 1,
                    'is_online' => 1,
                    'wages_type' => $employee_info['wages_type']
                );
                $result = $this->attendance->create($data);
            }
            if ($result) {
                return $this->responseSuccess(__('xin_theme_success'));
            } else {
                return $this->responseError(__('error_title'));
            }
        }
//        else if($clock_state=='check_out') {
//            $query = $this->attendance->check_user_attendance_clockout();
//            $clocked_out = $query;
//
//            $total_work_cin = Carbon::createFromFormat("Y-m-d H:i:s", $clocked_out->clock_in);
//            $total_work_cout = Carbon::createFromFormat("Y-m-d H:i:s", $curtime);
//
////            $total_work_cin =  new DateTime($clocked_out[0]->clock_in);
////            $total_work_cout =  new DateTime($curtime);
//
//            $interval_cin = $total_work_cout->diff($total_work_cin);
//            $hours_in   = $interval_cin->format('%h');
//            $minutes_in = $interval_cin->format('%i');
//            $total_work = $hours_in .":".$minutes_in;
//
//            $data = array(
//                'employee_id' => $employee_id,
//                'clock_out' => $curtime,
//                'clock_out_ip_address' => $request->ip(),
//                'clock_out_latitude' => $latitude,
//                'clock_out_longitude' => $longitude,
//                'clock_in_out' => '0',
//                'early_leaving' => $curtime,
//                'overtime' => $curtime,
//                'total_work' => $total_work
//            );
//            $id = $request->time_id;
//            $resuslt2 = $this->Timesheet_model->update_attendance_clockedout($data,$id);
//
//            if ($resuslt2 == TRUE) {
//                $Return['result'] = $this->lang->line('xin_success_clocked_out');
//                $Return['time_id'] = '';
//            } else {
//                $Return['error'] = $this->lang->line('xin_error_msg');
//            }
//
//        }

    }
}
