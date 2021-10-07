<?php
namespace App\Repositories;

use App\Models\Company;
use App\Models\Department;
use App\Models\Employee;
use App\Models\OfficeLocation;

class DepartmentRepository extends Repository
{
    public function getModel(): string
    {
        return Department::class;
    }

    public function getDepartmentByCompany($company_id, $name=null){
        $results = $this->model->where('company_id', $company_id);

        if($name != null){
            $results->where('department_name', 'LIKE', '%'.$name.'%');
        }
        $results = $results->get();

        return $results;
    }
    /**
     * @param $department_id
     */
    public function setTotalWorkings($department_id){
        // tổng số ngày phải làm của bộ phận
        //(*) mã bộ phận => tổng ngày phải làm trong tháng
        $list_partment = array(
            18 => 28,
        );
        if (array_key_exists($department_id, $list_partment) ==  true){
            return $list_partment[$department_id];
        }else{
            return false;
        }
    }

    public function listDepartment($paginateConfig, $request){
        $query = Department::with(['companyy', 'employeee', 'locationn']);
        $query_string = $request->query_string;
        if ($query_string != ''){
            $departments = Department::whereRaw("concat(department_name) like '%$query_string%' ")->get();
            $department_id = [];
            foreach ($departments as $id){
                $department_id[] = $id->department_id;
            }
            $companies = Company::whereRaw("concat(name) like '%$query_string%' ")->get();
            $company_id = [];
            foreach ($companies as $id){
                $company_id[] = $id->company_id;
            }
            $employees = Employee::whereRaw("concat(first_name, ' ', last_name) like '%$query_string%' ")
                ->orWhereRaw("concat(last_name, ' ', first_name) like '%$query_string%' ")->get();
            $user_id = [];
            foreach ($employees as $id){
                $user_id[] = $id->user_id;
            }
            $branchs = OfficeLocation::whereRaw("concat(location_name) like '%$query_string%' ")->get();
            $location_id = [];
            foreach ($branchs as $id){
                $location_id[] = $id->location_id;
            }
            if($department_id){
                $query->whereIn("department_id", $department_id);
            }else if($location_id){
                $query->whereIn("location_id", $location_id);
            }else if($company_id){
                $query->whereIn("company_id", $company_id);
            }else if($user_id){
                $query->whereIn("employee_id", $user_id);
            }else{$query->whereIn("department_id", $department_id);}
        }
        $listDepartment = $query->paginate($paginateConfig->getPerPage(), $paginateConfig->getColumns(), 'page', $paginateConfig->getPage());
        return $listDepartment;
    }

}
