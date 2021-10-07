<?php
namespace App\Repositories;

use App\Models\LeaveType;

class LeaveTypeRepository extends Repository
{
    public function getModel(): string
    {
        return LeaveType::class;
    }

    public function getLeaveType() {
        $leaveTypes = $this->model->pluck('type_name', 'leave_type_id');
        return $leaveTypes;
    }
    public function all_leave_types(){
        $query_all_types = LeaveType::all();
        return $query_all_types;
    }

    public function getLeaveTypeByCompany($company_id, $name=null){
        $results = $this->model->where('company_id', $company_id);

        if($name != null){
            $results->where('type_name', 'LIKE' ,'%'.$name.'%');
        }
        $results = $results->get();

        return $results;
    }
}
