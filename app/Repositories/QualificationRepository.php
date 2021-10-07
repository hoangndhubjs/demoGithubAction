<?php
namespace App\Repositories;

use App\Models\EmployeeImmigration;
use App\Models\EmployeeQualification;
use Illuminate\Support\Facades\Auth;

class QualificationRepository extends Repository
{
    public function getModel(): string
    {
        return EmployeeQualification::class;
    }

    public function getQualification($paginateConfig, $user_id) {
        $query = EmployeeQualification::where('employee_id', $user_id);

        $listQualification = $query->with(['education', 'language'])->paginate($paginateConfig->getPerPage(), $paginateConfig->getColumns(), 'page', $paginateConfig->getPage());

        return $listQualification;
    }
}
