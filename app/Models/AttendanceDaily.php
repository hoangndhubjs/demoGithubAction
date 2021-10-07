<?php
namespace App\Models;

use App\Traits\HasCompositePrimaryKey;

class AttendanceDaily extends CacheModel
{
    use HasCompositePrimaryKey;
    protected $table = 'attendance_daily';
    public $incrementing = false;
    public $timestamps = false;
    protected $primaryKey = ['employee_id', 'day'];
    protected $guarded = [];
    protected $appends = ['is_full_attendance', 'is_leave', 'is_leave_with_request', 'is_basic'];
    protected $casts = [
        'is_holiday' => 'boolean',
        'is_half_attendance' => 'boolean'
    ];

    public function employee() {
        return $this->belongsTo(Employee::class, 'employee_id', 'user_id');
    }

    public function getIsFullAttendanceAttribute() {
        return $this->attendance_count == 1;
    }

    public function getIsLeaveAttribute() {
        return $this->attendance_count == 0;
    }

    public function getIsLeaveWithRequestAttribute() {
        return $this->total_request_leave_full >= 1;
    }

    /**
     * Nhan vien chinh thuc
     **/
    public function getIsBasicAttribute() {
        return $this->wages_type === 1;
    }

}
