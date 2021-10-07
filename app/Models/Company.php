<?php
namespace App\Models;

class Company extends CacheModel
{
    protected $table = 'companies';
    protected $primaryKey = 'company_id';
    protected $guarded = [];

    public function company_type()
    {
        return $this->hasOne(CompanyType::class, 'type_id', 'type_id');
    }

    public function countries()
    {
        return $this->hasOne(Country::class, 'country_id', 'company_id');
    }

    public function office_location(){
        return $this->belongsTo(OfficeLocation::class, 'company_id', 'company_id');
    }

    public function department(){
        return $this->belongsTo(Department::class, 'company_id', 'company_id');
    }

    public function designations(){
        return $this->belongsTo(Designation::class, 'company_id', 'company_id');
    }

    public function announcements(){
        return $this->belongsTo(Announcement::class, 'company_id', 'company_id');
    }

    public function companypolicy(){
        return $this->belongsTo(CompanyPolicy::class, 'company_id', 'company_id');
    }
}
