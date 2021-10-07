<?php

namespace App\Models;



class ExpenseType extends Model
{
    protected $table = 'expense_type';
    protected $primaryKey = 'expense_type_id';
    protected $guarded = [];

    public function company() {
        return $this->belongsTo(Company::class, 'company_id', 'company_id');
    }
}
