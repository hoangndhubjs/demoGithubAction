<?php
namespace App\Models;

class TicketsComment extends CacheModel
{
    protected $table = 'tickets_comments';
    protected $primaryKey = 'comment_id';
}
