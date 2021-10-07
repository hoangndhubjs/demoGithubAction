<?php
namespace App\Models;


class Department extends CacheModel
{
    protected $table = 'departments';

    protected $primaryKey = 'department_id';
    protected $guarded = [];

    public function companyy(){
        return $this->hasOne(Company::class, 'company_id', 'company_id');
    }

    public function locationn(){
        return $this->hasOne(OfficeLocation::class, 'location_id', 'location_id');
    }

    public function employeee(){
        return $this->hasOne(Employee::class, 'user_id', 'employee_id');
    }

    public function designations(){
        return $this->belongsTo(Designation::class, 'department_id', 'department_id');
    }

    public function announcements(){
        return $this->belongsTo(Announcement::class, 'department_id', 'department_id');
    }
}
