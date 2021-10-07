<?php
namespace App\Models;

class TerminationType extends CacheModel
{
    protected $table = 'termination_type';
    protected $primaryKey = 'termination_type_id';
    protected $guarded = [];
}
