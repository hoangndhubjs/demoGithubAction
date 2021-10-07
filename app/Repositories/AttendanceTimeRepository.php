<?php

namespace App\Repositories;

use App\Models\AttendanceTime;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AttendanceTimeRepository extends Repository
{
    public function getModel(): string
    {
        return AttendanceTime::class;
    }

    public function getAttendanceTimesOf($employeeId, $date)
    {
        return $this->model->where([
            'employee_id' => $employeeId,
            'attendance_date' => $date
        ])->get();
    }

    /**
     * @param $user_id
     * @param null $date
     *
     * Kiểm tra xem đã có dữ liệu chẹck_in lần đầu tiên trong ngày chưa
     *
     */
    public function checkUserAttendanceByUserId($user_id, $date = null)
    {
        if (!$date) {
            $today_date = date('Y-m-d');
        } else {
            $today_date = date('Y-m-d', strtotime($date));
        }

        $query = AttendanceTime::where('employee_id', $user_id)->where('attendance_date', $today_date)->orderBy('time_attendance_id', 'desc')->limit(1)->get();

        return $query;
    }


    /**
     * kiểm tra xem lần cuối user logout là khi nào
     * để khi checkin tính toán thời gian nghỉ giữa giờ
     */
    public function checkUserAttendanceLastCheckin($user_id, $date = null)
    {
        if ($date) {
            $convert_date = date('Y-m-d', strtotime($date));
        } else {
            $convert_date = date('Y-m-d');
        }
        $query = AttendanceTime::where('employee_id', $user_id)->where('attendance_date', $convert_date)->where('clock_out', '!=', '')->orderBy('time_attendance_id', 'desc')->limit(1)->get();
        return $query;
    }

    /**
     * kiểm tra xem trạng thái lần cuối check-in
     * cho API
     */
    public function checkUserAttendanceClockOutByData($user_id, $date = null)
    {
        if ($date) {
            $convert_date = date('Y-m-d', strtotime($date));
        } else {
            $convert_date = date('Y-m-d');
        }
        $query = AttendanceTime::where('employee_id', $user_id)->where('attendance_date', $convert_date)->orderBy('time_attendance_id', 'desc')->first();

        return $query;
    }
    /**
     * check user attendance in day is had
     */
    public function check_user_attendance(){
        $today_date = Carbon::now()->format("Y-m-d");
        $user_id = Auth::id();
        $query = $this->model->where([
            'employee_id' => $user_id,
            'attendance_date' => $today_date
        ])->orderBy('time_attendance_id', 'desc')->first();
        return $query;
    }
    /**
     *
     */
    public function check_user_attendance_clockout(){
        $today_date = Carbon::now()->format("Y-m-d");
        $user_id = Auth::id();
        $query = $this->model->where([
            'employee_id' => $user_id,
            'attendance_date' => $today_date,
            'clock_out' => ''
        ])->orderBy('time_attendance_id', 'desc')->first();
        return $query;
    }
}
