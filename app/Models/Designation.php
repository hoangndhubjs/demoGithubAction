<?php
namespace App\Models;

use App\Announcement;

class Designation extends CacheModel
{
    protected $table = 'designations';

    protected $primaryKey = 'designation_id';
    protected $guarded  = [];

    public function companyAsset(){
        return $this->hasOne(Company::class, 'company_id', 'company_id');
    }

    public function departmentAsset(){
        return $this->hasOne(Department::class, 'department_id', 'department_id');
    }

}
