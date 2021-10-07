<?php
namespace App\Models;

class Jobs extends CacheModel
{
    protected $table = 'jobs';
    protected $primaryKey = 'job_id';
}
