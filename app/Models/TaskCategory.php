<?php
namespace App\Models;

class TaskCategory extends CacheModel
{
    protected $table = 'task_categories';
    protected $primaryKey = 'task_category_id';
}
