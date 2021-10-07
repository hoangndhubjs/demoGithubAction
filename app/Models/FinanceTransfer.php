<?php
namespace App\Models;

class FinanceTransfer extends CacheModel
{
    protected $table = 'finance_transfer';
    protected $primaryKey = 'transfer_id';
}
