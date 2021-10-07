<?php
namespace App\Models;

class Country extends CacheModel
{
    protected $table = 'countries';
    protected $primaryKey = 'country_id';

    public function companies()
    {
        return $this->belongsTo(Company::class, 'company_id', 'country_id');
    }

    public function office_location(){
        return $this->belongsTo(OfficeLocation::class, 'country', 'country_id');
    }
}
