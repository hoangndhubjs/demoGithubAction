<?php
namespace App\Models;

class FileManagerSettings extends CacheModel
{
    protected $table = 'file_manager_settings';
    protected $primaryKey = 'setting_id';

    protected $guarded = [];
}
