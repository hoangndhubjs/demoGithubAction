<?php
namespace App\Models;

class SalaryOtherPayment extends CacheModel
{
    protected $table = 'salary_other_payments';
    protected $primaryKey = 'other_payments_id';
    protected $guarded = [];
}
