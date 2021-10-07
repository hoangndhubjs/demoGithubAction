<?php
namespace App\Models;

class FinanceTransactions extends CacheModel
{
    protected $table = 'finance_transactions';
    protected $primaryKey = 'transaction_id';
}
