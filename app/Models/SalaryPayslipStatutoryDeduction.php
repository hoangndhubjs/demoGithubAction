<?php
namespace App\Models;

class SalaryPayslipStatutoryDeduction extends CacheModel
{
    protected $table = 'salary_payslip_statutory_deductions';
    protected $primaryKey = 'payslip_deduction_id';
    protected $guarded = [];
}
