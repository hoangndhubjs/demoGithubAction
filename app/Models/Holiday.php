<?php
namespace App\Models;

class Holiday extends CacheModel
{
    protected $table = 'holidays';
    protected $primaryKey = 'holiday_id';
    protected $appends = ['status'];

    const IS_PUBLISH = 1;
    const IS_NOT_PUBLISH = 0;

    const STATUS_LABELS = [
        self::IS_PUBLISH => 'Đã xuất bản',
        self::IS_NOT_PUBLISH => 'Chưa xuất bản',
    ];

    public function getStatusAttribute(){
        return __(self::STATUS_LABELS[$this->is_publish]);
    }
}
