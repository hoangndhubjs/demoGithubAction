<?php

namespace App\Models;

class EmployeeBankaccount extends CacheModel {

    protected $table = 'employee_bankaccount';
    protected $primaryKey = 'bankaccount_id';

    protected $guarded = [];

    public function employee(){
        return $this->belongsTo(Employee::class, 'employee_id', 'user_id');
    }

    public function advanceSalary(){
        return $this->hasOne(AdvanceSalaries::class, 'employee_id', 'empployee_id');
    }
}
