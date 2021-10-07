<?php
namespace App\Models;

class User extends CacheModel
{
    protected $table = 'user';
    protected $primaryKey = 'user_id';
}
