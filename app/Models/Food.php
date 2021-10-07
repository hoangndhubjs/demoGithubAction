<?php
namespace App\Models;

class Food extends CacheModel
{
    protected $table = 'datcom_monan';
    protected $guarded = [];

    const TYPE_PRIMARY= 1;
    const TYPE_SECONDARY = 2;
    const TYPE_TERTIARY = 3;
}