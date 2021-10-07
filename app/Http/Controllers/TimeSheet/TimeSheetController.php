<?php
namespace App\Http\Controllers\TimeSheet;

use App\Http\Controllers\Controller;
use App\Models\Leave;
use App\Repositories\EmployeesPheptonRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Traits\DatatableResponseable;
use App\Classes\PaginateConfig;
use App\Repositories\AttendanceRepository;
use App\Repositories\AttendanceDailyRepository;
use Illuminate\Support\Facades\Auth;
use Carbon\CarbonPeriod;

class TimeSheetController extends Controller
{
    use DatatableResponseable;
    private $attendance;
    private $attendance_monthly;

    public function __construct(AttendanceRepository $attendance, AttendanceDailyRepository $attendance_monthly) {
        $this->attendance = $attendance;
        $this->attendance_monthly = $attendance_monthly;
    }

    public function attendance() {
        $page_title = __("dashboard_attendance");
        return view('pages.timesheet.attendance', compact('page_title'));
    }

    public function attendance_monthly() {
        $page_title = __("dashboard_attendance");
        return view('pages.timesheet.attendance_monthly', compact('page_title'));
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
        $startDate = $request->get('start_date');
        $endDate = $request->get("end_date");
        $userId = Auth::id();
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
                if (!($eventLabel == '' || $eventLabel == 'P' || $eventLabel == 'K')) {
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
            'online' => 'attendance-online',
        );
        $labels = array(
            'full' => 'X',
            'half' => 'N',
            'holiday' => 'L',
            'leave_requested' => 'P',
            'leave' => 'K',
            'businessGoOn' => 'C',
            'sunday' => '',
            'online' => 'O',
            'error' => 'Error',
        );
        $color = $colors['leave'];
        $label = $labels['leave'];
        if (!$attendance) {
            return $color;
        } else {
            if ($attendance->is_go_on_business) {
                $color = $colors['businessGoOn'];
                $label = $labels['businessGoOn'];
            } else if ($attendance->is_online == 2) {
                $color = $colors['online'];
                $label = $labels['error'];
            } else if ($attendance->is_online) {
                $color = $colors['online'];
                $label = $labels['online'];
            } else if ($attendance->is_holiday) {
                $color = $colors['holiday'];
                $label = $labels['holiday'];
            }  else if ($attendance->is_leave_with_request) {
                $color = $colors['leave_requested'];
                $label = $labels['leave_requested'];
            } else if($attendance->is_full_attendance) {
                $color = $colors['full'];
                $label = $labels['full'];
            } else if ($attendance->is_half_attendance) {
                $color = $colors['half'];
                $label = $labels['half'];
            } else if($date->dayOfWeek === 0) {
                $color = $colors['sunday'];
                $label = $labels['sunday'];
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
        $endDate = $month."-".date('t', strtotime(strtotime($startDate)));
        $userId = Auth::id();
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
}
