<?php
namespace App\Repositories;

use App\Models\AttendanceTimeRequestModel;
use Carbon\Carbon;

class OvertimeRepository extends Repository
{
    public function getModel(): string
    {
        return AttendanceTimeRequestModel::class;
    }

    public function getApprovedOvertimeRequestOf($employee_id, $date) {
        return $this->model->where([
            'employee_id' => $employee_id,
            'is_approved' => AttendanceTimeRequestModel::STATUS_APPROVED,
            'request_date' => $date
        ])->get();
    }

}
