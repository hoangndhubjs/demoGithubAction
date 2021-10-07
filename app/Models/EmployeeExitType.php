<?php

namespace App\Models;

class EmployeeExitType extends CacheModel
{
    protected $table = 'employee_exit_type';
    protected $primaryKey = 'exit_type_id';
    protected $guarded = [];
}
