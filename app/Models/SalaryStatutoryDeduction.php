<?php
namespace App\Models;

class SalaryStatutoryDeduction extends CacheModel
{
    protected $table = 'salary_statutory_deductions';
    protected $primaryKey = 'statutory_deductions_id';
    protected $guarded = [];
}
