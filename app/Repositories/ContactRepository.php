<?php
namespace App\Repositories;

use App\Models\EmployeeContact;
use Illuminate\Support\Facades\Auth;

class ContactRepository extends Repository
{
    public function getModel(): string
    {
        return EmployeeContact::class;
    }

    public function getContact($paginateConfig, $user_id) {

        $query = EmployeeContact::where('employee_id', $user_id);

        $listContact = $query->paginate($paginateConfig->getPerPage(), $paginateConfig->getColumns(), 'page', $paginateConfig->getPage());

        return $listContact;
    }
}
