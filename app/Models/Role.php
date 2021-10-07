<?php
namespace App\Models;

class Role extends CacheModel
{
    protected $table = 'roles';
    protected $primaryKey = 'id';
    protected $guarded = [];

    public function company() {
        return $this->belongsTo(Company::class, 'company_id');
    }
}
