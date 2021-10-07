<?php

namespace App\Models;



class EmployeeContract extends Model
{
    protected $table = 'employee_contract';
    protected $primaryKey = 'contract_id';
    protected $guarded = [];

    public function contractType() {
        return $this->belongsTo(ContractType::class, 'contract_type_id', 'contract_type_id');
    }

    public function designation() {
        return $this->belongsTo(Designation::class, 'designation_id', 'designation_id');
    }
}
