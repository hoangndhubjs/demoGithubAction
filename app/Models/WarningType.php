<?php
namespace App\Models;

class WarningType extends CacheModel
{
    protected $table = 'warning_type';
    protected $primaryKey = 'warning_type_id';
    protected $guarded = [];
}
