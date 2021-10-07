<?php
namespace App\Models;

class SalaryAllowance extends CacheModel
{
    protected $table = 'salary_allowances';
    protected $primaryKey = 'allowance_id';
    protected $guarded = [];
}
