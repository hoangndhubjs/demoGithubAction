<?php
namespace App\Models;

class IncomeCategories extends CacheModel
{
    protected $table = 'income_categories';
    protected $primaryKey = 'category_id';
    protected $guarded = [];
}
