<?php
namespace App\Models;

class TasksComment extends CacheModel
{
    protected $table = 'tasks_comments';
    protected $primaryKey = 'comment_id';
}
