<?php
namespace App\Repositories;

use App\Models\AttendanceLog;

class AttendanceLogRepository extends Repository
{
    public function getModel(): string
    {
        return AttendanceLog::class;
    }
}
