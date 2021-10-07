<?php
namespace App\Models;

class TravelArrangementType extends CacheModel
{
    protected $table = 'travel_arrangement_type';
    protected $primaryKey = 'arrangement_type_id';
    protected $guarded = [];
}
