<?php
namespace App\Models;

class FinanceBankcash extends CacheModel
{
    protected $table = 'finance_bankcash';
    protected $primaryKey = 'bankcash_id';

    protected $guarded = [];
}
