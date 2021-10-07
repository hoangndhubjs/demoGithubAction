<?php
namespace App\Repositories;

use App\Models\EmployeeWorkExperience;
use Illuminate\Support\Facades\Auth;

class ExperienceRepository extends Repository
{
    public function getModel(): string
    {
        return EmployeeWorkExperience::class;
    }

    public function getExperience($paginateConfig, $user_id) {

        $query = EmployeeWorkExperience::where('employee_id', $user_id);

        $listExperience = $query->paginate($paginateConfig->getPerPage(), $paginateConfig->getColumns(), 'page', $paginateConfig->getPage());

        return $listExperience;
    }
}
