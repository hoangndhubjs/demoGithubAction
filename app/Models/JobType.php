<?php
namespace App\Models;

class JobType extends CacheModel
{
    protected $table = 'job_type';
    protected $primaryKey = 'job_type_id';
    protected $guarded = [];
}
