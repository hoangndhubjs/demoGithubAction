<?php
namespace App\Models;

class CompanyInfo extends CacheModel
{
    protected $table = 'company_info';
    protected $primaryKey = 'company_info_id';

    protected $guarded = [];
}
