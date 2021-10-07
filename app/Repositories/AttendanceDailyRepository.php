<?php
namespace App\Repositories;

use App\Models\AttendanceDaily;
use Carbon\Carbon;

class AttendanceDailyRepository extends Repository
{
    public function getModel(): string
    {
        return AttendanceDaily::class;
    }

    public function getAttendancesDailyOn($user_id, $from, $to) {
        $query = $this->model->where('employee_id', $user_id)
                ->where('day', '>=', $from)
                ->where('day', '<=', $to)
                ->orderBy('day', 'asc');
        return $query->get();
    }

    public function getSummaryAttendancesDailyOn ($user_id, $from, $to) {
    $query = $this->model->where('employee_id', $user_id)
        ->where('day', '>=', $from)
        ->where('day', '<=', $to)
        ->selectRaw('SUM(FLOOR(attendance_count)) as total_attendance, SUM(late_minutes) as total_late_minutes, SUM(early_leave_minutes) as total_early, SUM(total_request_leave_full + total_request_leave_full_no_salary) as total_leave, SUM(total_overtime) as total_overtime');
    return $query->first();
}

    public function getAttendanceDailyOn($employee_id, $date) {
        return $this->model->where('employee_id', $employee_id)
            ->where('day', $date)->first();
    }

    public function getPayslipSummaryOfMonth(Carbon $month, $userIds = array()) {
        $query = $this->model
            ->select("employee_id")
            ->selectRaw("wages_type as employee_type")
            ->selectRaw("SUM(attendance_count) as ngay_cong")
	        ->selectRaw("SUM(late_minutes) as phut_di_muon")
	        ->selectRaw("SUM(early_leave_minutes) as phut_ve_som")
	        ->selectRaw("SUM(is_half_attendance) as nua_cong")
	        ->selectRaw("SUM(total_request_leave_full) as ngay_nghi")
	        ->selectRaw("SUM(total_request_leave_half) as nghi_nua_ngay")
	        ->selectRaw("SUM(is_holiday) as ngay_le")
	        ->selectRaw("SUM(total_overtime) as overtime")
            # conditions
            ->whereRaw(sprintf("day >= '%s'", $month->startOfMonth()->format("Y-m-d")))
            ->whereRaw(sprintf("day <= '%s'", $month->endOfMonth()->format("Y-m-d")))
            # group conditions
            ->groupBy('employee_id', 'wages_type');
        if ($userIds) {
            $query->whereIn('employee_id', $userIds);
        }
        return $query->get();
    }
}
