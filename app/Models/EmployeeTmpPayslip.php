<?php

namespace App\Models;

use App\Traits\HasCompositePrimaryKey;


class EmployeeTmpPayslip extends Model
{
    use HasCompositePrimaryKey;
    public $incrementing = false;
    protected $table = 'employees_tmp_payslip';
    protected $primaryKey = ['employee_id', 'employee_type', 'month'];
    protected $guarded = [];

    public function employee(){
        return $this->belongsTo(Employee::class, 'employee_id', 'user_id');
    }
}
