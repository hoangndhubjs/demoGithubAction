<?php
namespace App\Models;

class LeaveApplication extends CacheModel
{
    protected $table = 'leave_applications';
    protected $primaryKey = 'leave_id';

    const LEAVE_FULL_DAY = 1;
    const LEAVE_HALF_DAY = 2;
    const LEAVE_IN_DAY = 3;
    const HAS_SALARY = 1;
    const NO_SALARY = 0;
    const LEAVE_MORNING = '0';
    const LEAVE_AFTERNOON = '1';
}
