<?php
namespace App\Classes\TimeChecker;

use App\Classes\Settings\SettingManager;
use App\Models\AttendanceDaily;
use App\Models\LeaveApplication;
use App\Models\OfficeShift;
use App\Repositories\AttendanceTimeRepository;
use App\Repositories\HolidayRepository;
use App\Repositories\LeaveRepository;
use App\Repositories\MealOrderRepository;
use App\Repositories\OvertimeRepository;
use Carbon\Carbon;
use App\Models\Employee;

class TimeChecker
{
    /**
     * @var Employee $employee
     */
    private $employee;
    /**
     * @var Carbon $date
     */
    private $date;
    /**
     * @var Carbon
     */
    private $startWorkTime;
    private $endWorkTime;
    private $startRestTime;
    private $endRestTime;
    /**
     * @var bool
     */
    private $hasRestTime;
    private $isGoOnBusiness;
    private $employeeHasLeaveInMorning;
    private $employeeHasLeaveInAfternoon;

    /**
     * @var \Illuminate\Support\Collection
     */
    private $listLeaveRequestTimes;

    const GO_ON_BUSINESS_ID = 6;

    /**
     * @var int
     */
    private $totalRequestLeaveFull;
    private $totalRequestLeaveHalf;
    private $totalRequestLeaveInDay;
    private $totalRequestLeaveFullNoSalary;
    private $totalRequestLeaveHalfNoSalary;
    private $totalRequestLeaveInDayNoSalary;
    private $attendance = 0;
    private $lateMinutes = 0;
    private $earlyLeaveMinutes = 0;
    private $isOnline = 0;
    private $wages_type = 1;

    /**
     * @var Carbon|null
     */
    private $checkinTime;
    private $checkoutTime;
    /**
     * @var bool
     */
    private $isHoliday = false;
    /**
     * @var int
     */
    private $totalOrders;
    private $totalOrdersAmount;
    /**
     * @var string
     */
    private $totalOTAttendance;


    public function __construct(Employee $employee, Carbon $date) {
        $this->employee = $employee;
        $this->date = $date;
        $this->calcAttendance();
    }
    public static function check(Employee $employee,Carbon $date)
    {
        return new TimeChecker($employee, $date);
    }

    protected function isHalfAttendance() {
        if ($this->attendance == 1 && $this->totalRequestLeaveHalf && !$this->totalRequestLeaveFull) {
            return 1;
        }
        if ($this->attendance == 0.5) {
            return 1;
        }
        return 0;
    }

    public function calcAttendance()
    {
        $this->_loadMealOrders();
        $this->_loadHolidays();
        if ($this->isEmployeeHasOfficeShift() && $this->isWorkingDay()) {
            $this->validateLeaveApplications();
            $this->checkEarlyAndLeaveMinutes();
            $this->_loadOTRequest();
            $data = array(
                'attendance_count' => $this->attendance,
                'wages_type' => $this->wages_type == 2 ? 2 : 1,
                'check_in_at' => $this->checkinTime ? $this->checkinTime->format("Y-m-d H:i:s") : null,
                'check_out_at' => $this->checkoutTime ? $this->checkoutTime->format("Y-m-d H:i:s") : null,
                'is_holiday' => $this->isHoliday ? 1 : 0,
                'total_order_rice' => $this->totalOrders,
                'total_order_rice_amount' => $this->totalOrdersAmount,
                'total_overtime' => $this->totalOTAttendance,
                'total_overtime_minutes' => 0,
                'total_request_leave_full' => $this->totalRequestLeaveFull,
                'total_request_leave_half' => $this->totalRequestLeaveHalf,
                'total_request_leave_in_day' => $this->totalRequestLeaveInDay,
                'total_request_leave_full_no_salary' => $this->totalRequestLeaveFullNoSalary,
                'total_request_leave_half_no_salary' => $this->totalRequestLeaveHalfNoSalary,
                'total_request_leave_in_day_no_salary' => $this->totalRequestLeaveInDayNoSalary,
                'late_minutes' => $this->lateMinutes,
                'early_leave_minutes' => $this->earlyLeaveMinutes,
                'is_half_attendance' => $this->isHalfAttendance(),
                'is_go_on_business' => $this->isGoOnBusiness ? 1 : 0,
                'is_online' => $this->attendance == 1 && $this->isOnline == 1 ? 1 : 0
            );
            # Auto full attendance for all BLD;
            $fullAttendanceEmployees = SettingManager::getOption('employees_full_attendance', []);
            if (in_array($this->employee->user_id, $fullAttendanceEmployees)) {
                $data['attendance_count'] = 1;
                $data['total_overtime'] = 0;
                $data['total_overtime_minutes'] = 0;
                $data['total_request_leave_full'] = 0;
                $data['total_request_leave_half'] = 0;
                $data['total_request_leave_in_day'] = 0;
                $data['total_request_leave_full_no_salary'] = 0;
                $data['total_request_leave_half_no_salary'] = 0;
                $data['total_request_leave_in_day_no_salary'] = 0;
                $data['late_minutes'] = 0;
                $data['early_leave_minutes'] = 0;
                $data['is_half_attendance'] = 0;
                $data['is_go_on_business'] = 0;
                $data['is_online'] = 0;
            }
        } else {
            $data = array(
                'attendance_count' => $this->attendance,
                'wages_type' => $this->wages_type == 2 ? 2 : 1,
                'check_in_at' => $this->checkinTime ? $this->checkinTime->format("Y-m-d H:i:s") : null,
                'check_out_at' => $this->checkoutTime ? $this->checkoutTime->format("Y-m-d H:i:s") : null,
                'is_holiday' => $this->isHoliday ? 1 : 0,
                'total_order_rice' => $this->totalOrders,
                'total_order_rice_amount' => $this->totalOrdersAmount,
                'total_overtime' => 0,
                'total_overtime_minutes' => 0,
                'total_request_leave_full' => 0,
                'total_request_leave_half' => 0,
                'total_request_leave_in_day' => 0,
                'total_request_leave_full_no_salary' => 0,
                'total_request_leave_half_no_salary' => 0,
                'total_request_leave_in_day_no_salary' => 0,
                'late_minutes' => 0,
                'early_leave_minutes' => 0,
                'is_half_attendance' => 0,
                'is_go_on_business' => 0,
                'is_online' => $this->attendance == 1 && $this->isOnline == 1 ? 1 : 0
            );
        }
        AttendanceDaily::updateOrCreate(
            ['employee_id' => $this->employee->user_id, 'day' => $this->date->format("Y-m-d")],
            $data
        );
    }

    protected function isWorkingDay() {
        $office_shift = $this->employee->office_shift;
        $day_name = strtolower($this->date->englishDayOfWeek);
        $column_time_in = sprintf("%s_in_time", $day_name);
        return isset($office_shift->$column_time_in) && !is_null($office_shift->$column_time_in) && $office_shift->$column_time_in != '';
    }

    protected function _loadOTRequest()
    {
        $OTRequests = app()->make(OvertimeRepository::class)->getApprovedOvertimeRequestOf($this->employee->user_id, $this->date->format("Y-m-d"));
        $this->totalOTAttendance = number_format($OTRequests->count()/2, 1);
    }

    protected function _loadHolidays()
    {
        $holidays = app()->make(HolidayRepository::class)->getHolidaysAt($this->date->format("Y-m-d"));
        if ($holidays->count() > 0) {
            $this->isHoliday = true;
        }
    }

    protected function _loadMealOrders()
    {
        $orders = app()->make(MealOrderRepository::class)->getApprovedOrdersOf($this->employee->user_id, $this->date->format('Y-m-d 00:00:00'));
        $totalOrders = 0;
        $totalOrderAmount = 0;
        foreach($orders as $order) {
            $totalOrders++;
            $totalOrderAmount += $order->price;
        }
        $this->totalOrders = $totalOrders;
        $this->totalOrdersAmount = $totalOrderAmount;
    }

    protected function getWageTypes() {
        $wages_type = $this->employee->wages_type;
//        if ($this->employee->end_trail_work && strtotime($this->employee->end_trail_work) >= intval($this->date->format("U"))) {
//            $wages_type = Employee::WAGE_TRIAL;
//        }
        return $wages_type;
    }

    protected function isEmployeeHasOfficeShift()
    {
        $office_shift = $this->employee->office_shift;
        if ($office_shift) {
            $this->_loadOfficeShift($office_shift);
            return true;
        }
        return false;
    }

    protected function validateLeaveApplications()
    {
        $applications = app()->make(LeaveRepository::class)->getApprovedLeaveApplicationsOf($this->employee->user_id, $this->date->format("Y-m-d"));
        $total_request_leave_full = 0;
        $total_request_leave_half = 0;
        $total_request_leave_in_day = 0;
        $total_request_leave_full_no_salary = 0;
        $total_request_leave_half_no_salary = 0;
        $total_request_leave_in_day_no_salary = 0;
        $this->listLeaveRequestTimes = collect();
        foreach($applications as $application) {
            if ($application->leave_time_types === LeaveApplication::LEAVE_FULL_DAY) {
                $application->is_salary === LeaveApplication::HAS_SALARY && $total_request_leave_full++;
                $application->is_salary === LeaveApplication::NO_SALARY && $total_request_leave_full_no_salary++;
                if ($application->leave_type_id === self::GO_ON_BUSINESS_ID) {
                    $this->isGoOnBusiness = true;
                }
            } elseif ($application->leave_time_types === LeaveApplication::LEAVE_HALF_DAY) {
                $application->is_salary === LeaveApplication::HAS_SALARY && $total_request_leave_half++;
                $application->is_salary === LeaveApplication::NO_SALARY && $total_request_leave_half_no_salary++;
                $application->type_half_day === LeaveApplication::LEAVE_MORNING && $this->employeeHasLeaveInMorning = true;
                $application->type_half_day === LeaveApplication::LEAVE_AFTERNOON && $this->employeeHasLeaveInAfternoon = true;
                if ($application->leave_type_id === self::GO_ON_BUSINESS_ID) {
                    $this->isGoOnBusiness = true;
                }
            } elseif ($application->leave_time_types === LeaveApplication::LEAVE_IN_DAY) {
                $application->is_salary === LeaveApplication::HAS_SALARY && $total_request_leave_in_day++;
                $application->is_salary === LeaveApplication::NO_SALARY && $total_request_leave_in_day_no_salary++;
                # convert date to carbon
                $from_date = Carbon::createFromFormat("Y-m-d", $application->from_date);
                $to_date = Carbon::createFromFormat("Y-m-d", $application->to_date);
                $from_time = Carbon::createFromTimestamp(strtotime($application->from_time))->setDateFrom($from_date);
                $to_time = Carbon::createFromTimestamp(strtotime($application->to_time))->setDateFrom($to_date);
                $this->listLeaveRequestTimes->put($from_time->format("Y-m-d H:i:s"), $to_time->format("Y-m-d H:i:s"));
            }
        }
        $this->totalRequestLeaveFull = $total_request_leave_full;
        $this->totalRequestLeaveHalf = $total_request_leave_half;
        $this->totalRequestLeaveInDay = $total_request_leave_in_day;
        $this->totalRequestLeaveFullNoSalary = $total_request_leave_full_no_salary;
        $this->totalRequestLeaveHalfNoSalary = $total_request_leave_half_no_salary;
        $this->totalRequestLeaveInDayNoSalary = $total_request_leave_in_day_no_salary;
    }


    protected function checkEarlyAndLeaveMinutes()
    {
        $this->_loadEmployeeCheckinCheckOut();
        if (!$this->isEmployeeLeaveFull()) {
            if ($this->isEmployeeTooLate($this->startWorkTime)) {
                if ($this->employeeHasLeaveInMorning && $this->totalRequestLeaveHalf) {
                    $this->attendance += 0.5;
                }
                if ($this->hasRestTime) {
                    $this->isEmployeeTooLate($this->endRestTime);
                }
            } else {
                $this->attendance += 0.5;
            }
            if ($this->isEmployeeLeaveEarly($this->endWorkTime)) {
                if ($this->employeeHasLeaveInAfternoon && $this->totalRequestLeaveHalf) {
                    $this->attendance += 0.5;
                }
                if ($this->hasRestTime) {
                    $this->isEmployeeLeaveEarly($this->startRestTime);
                }
            } else {
                $this->attendance += 0.5;
            }
            # remove it.
            if ($this->isGoOnBusiness) {
                $this->attendance += 0.5;
            }
            $this->attendance > 1 && $this->attendance = 1;
        } else {
            if ($this->totalRequestLeaveFull || $this->isGoOnBusiness) {
                $this->attendance = 1;
            } else {
                $this->attendance = 0;
            }

        }
    }

    protected function isEmployeeTooLate($from) {
        if (!$this->checkinTime) {
            return true;
        }
        $canLateAt = $this->getLateTimeAllowed($from);
        $maximumLate = SettingManager::getOption('maximum_late_time');
        $lateMinutes = $canLateAt->diffInMinutes($this->checkinTime, false);
        if ($maximumLate && $lateMinutes > $maximumLate) {
            return true;
        }
        $this->lateMinutes = $lateMinutes > 0 ? $lateMinutes : 0;
        return false;
    }

    protected function isEmployeeLeaveEarly($to) {
        if (!$this->checkoutTime) {
            return true;
        }
        $canLeaveAt = $this->getLeaveTimeAllowed($to);
        $maximumLeave = SettingManager::getOption('maximum_early_leave');
        $leaveMinutes = $this->checkoutTime->diffInMinutes($canLeaveAt, false);
        if ($leaveMinutes && $leaveMinutes > $maximumLeave) {
            return true;
        }
        $this->earlyLeaveMinutes = $leaveMinutes > 0 ? $leaveMinutes : 0;
        return false;
    }

    protected function getLateTimeAllowed($from)
    {
        $fromTimes = $this->listLeaveRequestTimes->keys()->sort();
        $startWork = $from->format('Y-m-d H:i:s');
        foreach($fromTimes as $time) {
            # only accept when user request times are continuous;
            if ($time === $startWork) {
                $startWork = $this->listLeaveRequestTimes->get($time);
            } else {
                break;
            }
        }
        return Carbon::createFromFormat("Y-m-d H:i:s", $startWork);
    }
    protected function getLeaveTimeAllowed($to)
    {
        $leaveTimes = $this->listLeaveRequestTimes->flip();
        $toTimes = $this->listLeaveRequestTimes->sortDesc();
        $endWork = $to->format("Y-m-d H:i:s");
        foreach($toTimes as $time) {
            # only accept when user request times are continuous;
            if ($time === $endWork) {
                $endWork = $leaveTimes->get($time);
            } else {
                break;
            }
        }
        return Carbon::createFromFormat("Y-m-d H:i:s", $endWork);
    }


    protected function isEmployeeLeaveFull() {
        $this->attendance = 0;
        if ($this->totalRequestLeaveFull + $this->totalRequestLeaveFullNoSalary > 0) {
            $this->attendance = 1;
            return true;
        }
        return false;
    }

    private function _loadOfficeShift(OfficeShift $officeShift) {
        $day_name = strtolower($this->date->englishDayOfWeek);
        $column_time_in = sprintf("%s_in_time", $day_name);
        $column_time_out = sprintf("%s_out_time", $day_name);
        $this->startWorkTime = Carbon::createFromTimestamp(strtotime($officeShift->$column_time_in))->setDateFrom($this->date);
        $this->endWorkTime = Carbon::createFromTimestamp(strtotime($officeShift->$column_time_out))->setDateFrom($this->date);
        $this->_loadRestTime();
    }

    private function _loadRestTime()
    {
        $this->startRestTime = SettingManager::getOption("lunch_rest_start") ? SettingManager::getOption("lunch_rest_start")->setDateFrom($this->date) : null;
        $this->endRestTime = SettingManager::getOption("lunch_rest_end") ? SettingManager::getOption("lunch_rest_end")->setDateFrom($this->date) : null;
        $this->hasRestTime = $this->startRestTime && $this->endRestTime;
    }

    private function _loadEmployeeCheckinCheckOut() {
        $attendanceTimeRepo = app()->make(AttendanceTimeRepository::class);
        $attendanceTimes = $attendanceTimeRepo->getAttendanceTimesOf($this->employee->user_id, $this->date->format("Y-m-d"));
        $min = $max = 0;
        foreach ($attendanceTimes as $log) {
            $check_in = strtotime($log->clock_in);
            $check_out = $log->clock_out ? strtotime($log->clock_out) : $check_in;
            $min === 0 && $min = $check_in;
            $max === 0 && $max = $check_out;
            $check_in < $min && $min = $check_in;
            $check_out > $max && $max = $check_out;
            if($this->isOnline === 0 && $log->is_online === 1) {
                $this->isOnline = 1;
            }
            if ($this->wages_type === 1 && $log->wages_type === 2) {
                $this->wages_type = 2;
            }
        }
        $min > 0 && $this->checkinTime = Carbon::createFromTimestamp($min);
        $max > 0 && $this->checkoutTime = Carbon::createFromTimestamp($max);
    }

}
