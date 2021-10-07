<?php
namespace App\Repositories;

use App\Repositories\SystemSettingRepository;
use App\Repositories\CompanyInfoRepository;
use App\Classes\Setting;

class SystemRepository
{
    private $system;
    private $company;
    
    public function __construct() {
        $this->system = app()->make(SystemSettingRepository::class);
        $this->company = app()->make(CompanyInfoRepository::class);
    }
    public function getSettingByDomain($domain = null) {
        $id = $this->getConfigIdFromDomain($domain);
        $systemSettings = $this->system->getSystemSetting($id);
        $companyInfo = $this->company->getCompanyInfo($id);
        return new Setting($systemSettings, $companyInfo);
    }
    
    public function getConfigIdFromDomain($domain) {
        return 1;
    }
}