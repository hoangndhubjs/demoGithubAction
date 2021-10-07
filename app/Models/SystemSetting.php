<?php
namespace App\Models;

class SystemSetting extends CacheModel
{
    protected $table = 'system_setting';
    protected $primaryKey = 'setting_id';

    protected $guarded = [];
}
