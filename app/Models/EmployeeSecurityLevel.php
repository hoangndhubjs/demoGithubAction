<?php

namespace App\Models;



class EmployeeSecurityLevel extends Model
{
    protected $table = 'employee_security_level';
    protected $primaryKey = 'security_level_id';
    protected $guarded = [];

    public function securityLevel() {
        return $this->belongsTo(SecurityLevel::class, 'security_type', 'type_id');
    }
}
