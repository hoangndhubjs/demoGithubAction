<?php
namespace App\Models;

class SalaryLoanDeduction extends CacheModel
{
    protected $table = 'salary_loan_deductions';
    protected $primaryKey = 'loan_deduction_id';
    protected $guarded = [];
}
