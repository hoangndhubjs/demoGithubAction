<?php

namespace App\Models;

class EmployeeImmigration extends CacheModel {

    protected $table = 'employee_immigration';
    protected $primaryKey = 'immigration_id';

    protected $guarded = [];

    public function documentType() {
        return $this->belongsTo(DocumentType::class, 'document_type_id', 'document_type_id');
    }

    public function country() {
        return $this->belongsTo(Country::class, 'country_id', 'country_id');
    }
}
