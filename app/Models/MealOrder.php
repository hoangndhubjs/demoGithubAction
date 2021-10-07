<?php
namespace App\Models;

class MealOrder extends CacheModel
{
    protected $table = 'datcom_employee_order';
    protected $appends = ['type_label', 'status_label'];
    protected $guarded = [];
    
    const TYPE_LUNCH = 1;
    const TYPE_DINNER = 2;
    const STATUS_PENDING = 0;
    const STATUS_CONFIRMED = 1;
    
    const TYPE_LABELS = [
        self::TYPE_LUNCH => 'Bữa trưa',
        self::TYPE_DINNER => 'Bữa tối'
    ];
    
    const STATUS_LABELS = [
        self::STATUS_PENDING => 'Đang chờ',
        self::STATUS_CONFIRMED => 'Đã chốt'
    ];

    public function employee() {
        return $this->belongsTo(Employee::class, 'user_id', 'user_id');
    }
    
    public function isLunch() {
        return $this->type === self::TYPE_LUNCH;
    }
    
    public function isDinner() {
        return $this->type === self::TYPE_DINNER;
    }
    
    public function getTypeLabelAttribute() {
        return __(self::TYPE_LABELS[$this->type]);
    }
    
    public function getStatusLabelAttribute() {
        return __(self::STATUS_LABELS[$this->status]);
    }
}
