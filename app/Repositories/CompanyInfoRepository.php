<?php
namespace App\Repositories;

use App\Models\CompanyInfo;
use App\Models\Country;

class CompanyInfoRepository extends Repository
{
    public function getModel(): string
    {
        return CompanyInfo::class;
    }

    public function getSettingFromId($id) {
        return $this->model->find($id);
    }

    public function getCompanySettingInfo($id) {
        $companyInfo = CompanyInfo::where('company_info_id', $id)->first();

        return $companyInfo;
    }

    public function get_countries(){
        $country = Country::all();

        return $country;
    }
}
