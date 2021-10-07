<?php
namespace App\Repositories;

use App\Models\Company;
use App\Models\CompanyPolicy;
use App\Models\Employee;

class CompanyPolicyRepository extends Repository
{
    public function getModel(): string
    {
        return CompanyPolicy::class;
    }

    /**
     * @param $company_id
     * @param null $name
     * @return mixed
     */

    public function listCompanyPolicy($paginateConfig, $request){
        $query = CompanyPolicy::with(['companyAsset', 'employeeAsset']);
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
            $policies = CompanyPolicy::whereRaw("concat(title) like '%$query_string%' ")->get();
            $policy_id = [];
            foreach ($policies as $id){
                $policy_id[] = $id->policy_id;
            }
            if($policy_id){
                $query->whereIn("policy_id", $policy_id);
            }else if($company_id){
                $query->whereIn("company_id", $company_id);
            }else if($user_id){
                $query->whereIn("added_by", $user_id);
            }else{$query->whereIn("policy_id", $policy_id);}
        }
        $listCompanyPolicy = $query->paginate($paginateConfig->getPerPage(), $paginateConfig->getColumns(), 'page', $paginateConfig->getPage());
        return $listCompanyPolicy;
    }
}
