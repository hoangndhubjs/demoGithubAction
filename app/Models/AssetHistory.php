<?php

namespace App\Models;



class AssetHistory extends Model
{
    protected $table = 'assets_history';
    protected $guarded = [];
    public $timestamps = false;

    public function employee() {
        return $this->belongsTo(Employee::class, 'employee_id', 'user_id');
    }
}
