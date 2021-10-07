<?php
namespace App\Models;

class OfficeLocation extends CacheModel
{
    protected $table = 'office_location';
    protected $primaryKey = 'location_id';
    protected $guarded = [];

    public function company(){
        return $this->hasOne(Company::class, 'company_id', 'company_id');
    }

    public function employee(){
        return $this->hasOne(Employee::class, 'user_id', 'location_head');
    }

    public function employee_addedby(){
        return $this->hasOne(Employee::class, 'user_id', 'added_by');
    }

    public function countryy(){
        return $this->hasOne(Country::class, 'country_id', 'country');
    }

    public function announcements(){
        return $this->belongsTo(Announcement::class, 'location_id', 'location_id');
    }
}
