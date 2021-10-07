<?php
namespace App\Models;

use Illuminate\Support\Facades\Auth;
//use App\Models\OfficeShift;
class SalaryPayslip extends CacheModel
{
    protected $table = 'salary_payslips';
    protected $primaryKey = 'payslip_id';
    protected $casts = [
        'basic_salary' => 'integer',
        'employee_id' => 'integer'
    ];
    protected $guarded = [];

    public function getBasicSalaryAttribute() {
        return intval(str_replace('.','', $this->attributes['basic_salary']));
    }
    public function employeeSalary() {
        return $this->belongsTo(Employee::class, 'employee_id', 'user_id');
    }
    public function employeeCompany(){
        return $this->belongsTo(Company::class, 'company_id', 'company_id');
    }
    public function designation() {
        return $this->belongsTo(Designation::class, 'designation_id', 'designation_id');
    }
    public function department() {
        return $this->belongsTo(Department::class, 'department_id', 'department_id');
    }
    public function bankAccount(){
        return $this->belongsTo(EmployeeBankaccount::class, 'employee_id', 'employee_id');
    }
    public function employee(){
        return $this->belongsTo(Employee::class, 'employee_id', 'user_id');
    }
}
