<?php
namespace App\Repositories;

use App\Models\Company;
use App\Models\Employee;
use App\Models\OfficeLocation;

class LocationRepository extends Repository
{
    public function getModel(): string
    {
        return OfficeLocation::class;
    }

    public function getLocationByCompany($company_id, $name=null){
        $results = $this->model->where('company_id', $company_id);

        if($name != null){
            $results->where('location_name', 'LIKE' ,'%'.$name.'%');
        }
        $results = $results->get();

        return $results;
    }

    public function listLocation($paginateConfig, $request){
        $query = OfficeLocation::with(['company', 'employee', 'countryy', 'employee_addedby']);
        $query_string = $request->query_string;
        if ($query_string != ''){
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
            if($location_id){
                $query->whereIn("location_id", $location_id);
            }else if($company_id){
                $query->whereIn("company_id", $company_id);
            }else if($user_id){
                $added = [];
                foreach($user_id as $id){
                    if(count(OfficeLocation::where("added_by", $id)->get()) !== 0) {
                        $added[] = OfficeLocation::where("added_by", $id)->get();
                    }
                }
                if(count($added) !== 0){ $query->whereIn("added_by", $user_id);
                }else { $query->whereIn("location_head", $user_id);}
            }else{$query->whereIn("location_id", $location_id);}
        }
        $listLocation = $query->paginate($paginateConfig->getPerPage(), $paginateConfig->getColumns(), 'page', $paginateConfig->getPage());
        return $listLocation;
    }
}
