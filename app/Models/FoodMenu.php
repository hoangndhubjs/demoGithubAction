<?php
namespace App\Models;

class FoodMenu extends CacheModel
{
    protected $table = 'datcom';
    protected $guarded = [];
    
    const TYPE_LUNCH = 1;
    const TYPE_DINNER = 2;

    const TYPE_LABELS = [
        self::TYPE_LUNCH => 'Bữa trưa',
        self::TYPE_DINNER => 'Bữa tối'
    ];

    const YET_PEGGED = 0;
    const PEGGED = 1;

    const TYPE_STATUS = [
        self::YET_PEGGED => 'Chưa chốt',
        self::PEGGED => 'Đã chốt'
    ];
}
