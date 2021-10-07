<?php
namespace App\Repositories;

use App\Models\EmployeeContract;

class EmployeeContractRepository extends Repository
{
    public function getModel(): string
    {
        return EmployeeContract::class;
    }

    public function getEmployeeContract($paginateConfig, $user_id) {

        $query = EmployeeContract::where('employee_id', $user_id);

        $listEmployeeContract = $query->with(['contractType', 'designation'])->paginate($paginateConfig->getPerPage(), $paginateConfig->getColumns(), 'page', $paginateConfig->getPage());

        return $listEmployeeContract;
    }
}
