<?php

namespace App\Models;

class Announcement extends CacheModel
{
    protected $table = 'announcements';

    protected $primaryKey = 'announcement_id';
    protected $guarded  = [];

    public function companyAsset(){
        return $this->hasOne(Company::class, 'company_id', 'company_id');
    }
    public function departmentAsset(){
        return $this->hasOne(Department::class, 'department_id', 'department_id');
    }
    public function  locationAsset(){
        return $this->hasOne(OfficeLocation::class, 'location_id', 'location_id');
    }
    public function employeeAsset(){
        return $this->hasOne(Employee::class, 'user_id', 'published_by');
    }
}
