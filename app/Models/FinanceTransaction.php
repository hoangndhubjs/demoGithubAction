<?php
namespace App\Models;

class FinanceTransaction extends CacheModel
{
    protected $table = 'finance_transaction';
    protected $primaryKey = 'transaction_id';
}
