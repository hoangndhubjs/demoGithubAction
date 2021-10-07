<?php
namespace App\Models;

class FinancePayers extends CacheModel
{
    protected $table = 'finance_payers';
    protected $primaryKey = 'payer_id';
}
