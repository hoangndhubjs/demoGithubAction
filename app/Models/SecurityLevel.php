<?php
namespace App\Models;

class SecurityLevel extends CacheModel
{
    protected $table = 'security_level';
    protected $primaryKey = 'type_id';
    protected $guarded = [];
}
