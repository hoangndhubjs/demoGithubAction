<?php
namespace App\Models;

class HourlyTemplate extends CacheModel
{
    protected $table = 'hourly_templates';
    protected $primaryKey = 'hourly_rate_id';
}
