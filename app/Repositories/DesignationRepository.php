<?php
namespace App\Repositories;

use App\Models\Company;
use App\Models\Department;
use App\Models\Designation;

class DesignationRepository extends Repository
{
    public function getModel(): string
    {
        return Designation::class;
    }

    /**
     * @param $company_id
     * @param $department_id
     * @param null $name
     * @return mixed
     */
    public function getDesignation($company_id, $department_id, $name=null){
        $results = $this->model->where('company_id', $company_id)
            ->where('department_id', $department_id);

        if($name != null){
            $results->where('designation_name', 'LIKE' ,'%'.$name.'%');
        }
        $results = $results->get();

        return $results;
    }
    public function listDesignation($paginateConfig, $request){
        $query = Designation::with(['companyAsset', 'departmentAsset']);
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
            $designation = Designation::whereRaw("concat(designation_name) like '%$query_string%' ")->get();
            $designation_id = [];
            foreach ($designation as $id){
                $designation_id[] = $id->designation_id;
            }
            if($department_id){
                $query->whereIn("department_id", $department_id);
            }else if($company_id){
                $query->whereIn("company_id", $company_id);
            }else if($designation_id){
                $query->whereIn("designation_id", $designation_id);
            }else{$query->whereIn("designation_id", $designation_id);}
        }
        $listDesignation = $query->paginate($paginateConfig->getPerPage(), $paginateConfig->getColumns(), 'page', $paginateConfig->getPage());
        return $listDesignation;
    }
}
