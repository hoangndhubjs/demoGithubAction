<?php

namespace App\Models;

class CompanyType extends CacheModel
{
    protected $table = 'company_type';
    protected $primaryKey = 'type_id';
    protected $name = 'name';
    protected $guarded = [];

    public function companies()
    {
        $this->belongsTo(Company::class, 'company_id', 'type_id');
    }
}