<?php
namespace App\Models;

class LeaveType extends CacheModel
{
    protected $table = 'leave_type';
    protected $primaryKey = 'leave_type_id';
    protected $guarded = [];
}
