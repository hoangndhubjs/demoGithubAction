<?php
namespace App\Models;

class SupportTicketFile extends CacheModel
{
    protected $table = 'support_ticket_files';
    protected $primaryKey = 'ticket_file_id';
}
