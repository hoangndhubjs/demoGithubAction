<?php
namespace App\Models;

class PayrollCustomField extends CacheModel
{
    protected $table = 'payroll_custom_fields';
    protected $primaryKey = 'payroll_custom_id';
}
