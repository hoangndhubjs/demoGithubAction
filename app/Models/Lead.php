<?php
namespace App\Models;

class Lead extends CacheModel
{
    protected $table = 'leads';
    protected $primaryKey = 'client_id';
}
