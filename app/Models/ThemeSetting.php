<?php
namespace App\Models;

class ThemeSetting extends CacheModel
{
    protected $table = 'theme_settings';
    protected $primaryKey = 'theme_settings_id';

    protected $guarded = [];

}
