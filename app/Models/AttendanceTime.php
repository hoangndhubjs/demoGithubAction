<?php
namespace App\Models;

class AttendanceTime extends CacheModel
{
    protected $table = 'attendance_time';
    protected $primaryKey = 'time_attendance_id';
    protected $guarded = [];

    public function employee() {
        return $this->belongsTo(Employee::class, 'employee_id', 'user_id');
    }
}
