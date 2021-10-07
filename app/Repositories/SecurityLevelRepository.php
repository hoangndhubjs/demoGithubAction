<?php
namespace App\Repositories;

use App\Models\EmployeeSecurityLevel;

class SecurityLevelRepository extends Repository
{
    public function getModel(): string
    {
        return EmployeeSecurityLevel::class;
    }

    public function getSecurityLevel($paginateConfig, $user_id) {

        $query = EmployeeSecurityLevel::where('employee_id', $user_id);

        $listSecurity = $query->with(['securityLevel'])->paginate($paginateConfig->getPerPage(), $paginateConfig->getColumns(), 'page', $paginateConfig->getPage());

        return $listSecurity;
    }
}
