<?php
namespace App\Models;

class PaymentMethod extends CacheModel
{
    protected $table = 'payment_method';
    protected $primaryKey = 'payment_method_id';
}
