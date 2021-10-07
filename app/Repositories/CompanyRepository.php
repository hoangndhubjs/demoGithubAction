<?php
namespace App\Repositories;

use App\Models\Company;
use App\Models\CompanyType;
use Illuminate\Support\Facades\Auth;

class CompanyRepository extends Repository
{
    public function getModel(): string
    {
        return Company::class;
    }

    public function getCompany($id) {
        return $this->model->find($id);
    }
    public function get_all_companies(){
        return Company::all();
    }
    public function get_company_by_user(){
        if (Auth::user()->isAdmin()){
            $get_by_user = $this->model->all();
        }else{
            $get_by_user = $this->model->where('company_id', Auth::user()->company_id)->get();
        }
        return $get_by_user;
    }
    /**
     * get list company
     *
     * @return mixed
     */
    public function getListCompany()
    {
        $query = $this->model->select("company_id as id", 'name')
            ->pluck('name', 'id');
        return $query;
    }

    public function listCompany($paginateConfig, $request)
    {
        $query = Company::with(['company_type']);
        $query_string = $request->query_string;
        if ($query_string != ''){
            $companies = Company::whereRaw("concat(name) like '%$query_string%' ")
                ->orWhereRaw("concat(email) like '%$query_string%' ")->get();
            $company_id = [];
            foreach ($companies as $id){
                $company_id[] = $id->company_id;
            }
            $companytype = CompanyType::whereRaw("concat(name) like '%$query_string%' ")->get();
            $type_id = [];
            foreach ($companytype as $id){
                $type_id[] = $id->type_id;
            }
            if($company_id){
                $query->whereIn("company_id", $company_id);
            }else if($type_id){
                $query->whereIn("type_id", $type_id);
            }else{$query->whereIn("company_id", $company_id);}
        }
        $listCompany = $query->paginate($paginateConfig->getPerPage(), $paginateConfig->getColumns(), 'page', $paginateConfig->getPage());
        return $listCompany;
    }
}
