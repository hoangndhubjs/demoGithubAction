<?php
namespace App\Models;

class SupportTicket extends CacheModel
{
    protected $table = 'support_tickets';
    protected $primaryKey = 'ticket_id';
}
