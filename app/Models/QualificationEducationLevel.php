<?php
namespace App\Models;

class QualificationEducationLevel extends CacheModel
{
    protected $table = 'qualification_education_level';
    protected $primaryKey = 'education_level_id';
    protected $guarded = [];
}
