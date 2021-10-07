<?php
namespace App\Models;

class JobCategory extends CacheModel
{
    protected $table = 'job_categories';
    protected $primaryKey = 'category_id';
    protected $guarded = [];
}
