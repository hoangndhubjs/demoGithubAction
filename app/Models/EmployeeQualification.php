<?php
namespace App\Models;

class EmployeeQualification extends CacheModel
{
    protected $table = 'employee_qualification';
    protected $primaryKey = 'qualification_id';
    protected $guarded = [];

    public function education() {
        return $this->belongsTo(QualificationEducationLevel::class, 'education_level_id', 'education_level_id');
    }

    public function language() {
        return $this->belongsTo(QualificationLanguage::class, 'language_id', 'language_id');
    }
}
