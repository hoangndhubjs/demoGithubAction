<?php
namespace App\Repositories;

use App\Models\EmployeeImmigration;
use Illuminate\Support\Facades\Auth;

class ImmigrationRepository extends Repository
{
    public function getModel(): string
    {
        return EmployeeImmigration::class;
    }

    public function getImmigration($paginateConfig, $user_id) {

        $query = EmployeeImmigration::where('employee_id', $user_id);

        $listImmigration = $query->with(['documentType', 'country'])->paginate($paginateConfig->getPerPage(), $paginateConfig->getColumns(), 'page', $paginateConfig->getPage());

        return $listImmigration;
    }
}
