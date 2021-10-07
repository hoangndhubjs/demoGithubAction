<?php
namespace App\Models;

class AttendanceLog extends CacheModel
{

    protected $table = 'attendance_log';
    protected $primaryKey = 'alog_id';
    protected $guarded = [];

}
