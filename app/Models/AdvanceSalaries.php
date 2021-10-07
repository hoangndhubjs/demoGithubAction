<?php
namespace App\Models;

class AdvanceSalaries extends CacheModel
{
    protected $table = 'advance_salaries';
    protected $primaryKey = 'advance_salary_id';
    protected $guarded = [];

    public function employeeAsset() {
        return $this->belongsTo(Employee::class, 'employee_id', 'user_id');
    }
    public function companyAsset() {
        return $this->belongsTo(CompanyInfo::class, 'company_id', 'company_info_id');
    }
    public function bankAccount() {
        return $this->belongsTo(EmployeeBankaccount::class, 'employee_id', 'employee_id');
    }
}
