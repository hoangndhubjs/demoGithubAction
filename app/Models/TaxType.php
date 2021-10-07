<?php
namespace App\Models;

class TaxType extends CacheModel
{
    protected $table = 'tax_types';
    protected $primaryKey = 'tax_id';
}
