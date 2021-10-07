<?php
namespace App\Models;

class HrsaleInvoice extends CacheModel
{
    protected $table = 'hrsale_invoices';
    protected $primaryKey = 'invoice_id';
}
