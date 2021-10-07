<?php
namespace App\Models;

class QualificationSkill extends CacheModel
{
    protected $table = 'qualification_skill';
    protected $primaryKey = 'skill_id';
    protected $guarded = [];
}
