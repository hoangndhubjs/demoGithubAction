<?php
namespace App\Models;

class UsersRole extends CacheModel
{
    protected $table = 'user_roles';
    protected $primaryKey = 'role_id';
}
