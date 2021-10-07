<?php
namespace App\Services;

use App\Models\AttendanceDaily;
use Carbon\Carbon;

class LeaveInLawService
{
    public static function countPrevious(Carbon $date) {
        $yesterday = $date->subDays(1)->format("Y-m-d");
        return AttendanceDaily::where('day', $yesterday)->pluck('');
    }
}
