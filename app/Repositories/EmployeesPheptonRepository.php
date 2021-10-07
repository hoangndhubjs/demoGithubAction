<?php
namespace App\Repositories;

use App\Models\EmployeesPhepton;

class EmployeesPheptonRepository extends Repository
{
    public function getModel(): string
    {
        return EmployeesPhepton::class;
    }

    public function checkPhepTon($data){
        return $this->model->where('employee_id', $data['employee_id'])
            ->where('leave_type_id', $data['leave_type_id'])
            ->where('year', $data['year'])
            ->first();
    }

}

