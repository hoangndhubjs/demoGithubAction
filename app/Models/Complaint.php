<?php
namespace App\Models;

class Complaint extends CacheModel
{
    protected $table = 'employee_complaints';
    protected $primaryKey = 'complaint_id';
    protected $guarded = [];
    protected $appends = ['status_label'];

    const STATUS_PENDING = 0;
    const STATUS_APPROVED = 1;
    const STATUS_REJECT = 2;


    const STATUS_LABELS = [
        self::STATUS_PENDING => 'Đang chờ xử lý',
        self::STATUS_APPROVED => 'Đã chấp nhận',
        self::STATUS_REJECT => 'Bị từ chối'
    ];

    public function getStatusLabelAttribute() {
        return __(self::STATUS_LABELS[$this->status]);
    }

    public function company() {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function listStatus(){
        return self::STATUS_LABELS;
    }

    public function getComplaintAgainstAttribute($complaint_against){
        $complaint = @json_decode($complaint_against);
        if(gettype($complaint) != 'array'){
            $complaint_against = explode(',', $complaint_against);
        } else {
            $complaint_against = $complaint;
        }
        return $complaint_against;
    }

    public function setComplaintAgainstAttribute($complaint_against){
        $this->attributes['complaint_against'] = json_encode($complaint_against);
        return $this;
    }
}

