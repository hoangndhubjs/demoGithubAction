<?php
namespace App\Models;

class AttendanceMachine extends CacheModel
{

    protected $table = 'attendance_machine';
    protected $primaryKey = 'id';
    protected $guarded = [];

}
