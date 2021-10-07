<?php
namespace App\Models;

use Illuminate\Support\Facades\Auth;
//use App\Models\OfficeShift;
class SalaryPaychecks extends CacheModel
{
    protected $table = 'salary_paychecks';
    protected $primaryKey = 'salary_paycheck_id';
    protected $guarded = [];
    public $timestamps = false;

    public function employeeSalary() {
        return $this->belongsTo(Employee::class, 'employee_id', 'employee_id');
    }
}
