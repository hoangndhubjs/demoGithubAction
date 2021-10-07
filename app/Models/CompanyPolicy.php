<?php

namespace App\Models;

class CompanyPolicy extends CacheModel
{
    protected $table = 'company_policy';
    protected $primaryKey = 'policy_id';
    protected $guarded = [];


    public function companyAsset(){
        return $this->hasOne(Company::class, 'company_id', 'company_id');
    }

    public function employeeAsset(){
        return $this->hasOne(Employee::class, 'user_id', 'added_by');
    }
}
