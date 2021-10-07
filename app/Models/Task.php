<?php
namespace App\Models;

class Task extends CacheModel
{
    protected $table = 'tasks';
    protected $primaryKey = 'task_id';
}
