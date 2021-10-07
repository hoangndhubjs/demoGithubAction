<?php

namespace App\Models;

class Currency extends CacheModel
{
    protected $table = 'currencies';
    protected $primaryKey = 'currency_id';
    protected $guarded = [];
}
