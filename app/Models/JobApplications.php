<?php
namespace App\Models;

class JobApplication extends CacheModel
{
    protected $table = 'job_applications';
    protected $primaryKey = 'application_id';
}
