<?php
namespace App\Models;

class OfficeShift extends CacheModel
{
    protected $table = 'office_shift';
    protected $primaryKey = 'office_shift_id';
    protected $guarded = [];

    public function company() {
        return $this->belongsTo(Company::class, 'company_id', 'company_id');
    }
}
