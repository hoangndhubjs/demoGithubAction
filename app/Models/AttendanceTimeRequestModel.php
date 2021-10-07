<?php

namespace App\Models;


use App\Traits\SlackNotify;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;

class AttendanceTimeRequestModel extends CacheModel
{
    use Notifiable, SlackNotify;

    protected $table = 'attendance_time_request';
    protected $primaryKey = 'time_request_id';
    protected $guarded = [];
    protected $appends = ['status_label'];

    const STATUS_PENDING = 1;
    const STATUS_APPROVED = 2;
    const STATUS_REJECT = 0;


    const STATUS_LABELS = [
        self::STATUS_PENDING => 'Đang chờ xử lý',
        self::STATUS_APPROVED => 'Đã chấp nhận',
        self::STATUS_REJECT => 'Bị từ chối'
    ];

    public function getStatusLabelAttribute() {
        return __(self::STATUS_LABELS[$this->is_approved]);
    }

    public function getNotifyMessageSlack(){
        $full_name = Auth::user()->first_name.' '.Auth::user()->last_name;

        $notification = $full_name. " yêu cầu làm thêm giờ từ ". date('h:i A', strtotime($this->request_clock_in)) . " đến " . date('h:i A', strtotime($this->request_clock_out)) . " ngày " . date('d-m-Y', strtotime($this->request_date));

        return sprintf($notification);
    }

    public function getNotifyMessageSlackPrivate(){
        $full_name = Auth::user()->first_name.' '.Auth::user()->last_name;

        $notification = $full_name. " yêu cầu làm thêm giờ từ ". date('h:i A', strtotime($this->request_clock_in)) . " đến " . date('h:i A', strtotime($this->request_clock_out)) . " ngày " . date('d-m-Y', strtotime($this->request_date)). '. Lý do: '.$this->request_reason;

        return sprintf($notification);
    }

    public function employee() {
        return $this->belongsTo(Employee::class, 'employee_id', 'user_id');
    }
}
