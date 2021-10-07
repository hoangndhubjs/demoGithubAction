<?php
namespace App\Models;

use App\Traits\SlackNotify;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use \App\Models\CompanyInfo;

class Meeting extends CacheModel
{
    use Notifiable, SlackNotify;
    protected $table = 'meetings';
    protected $primaryKey = 'meeting_id';
    protected $guarded = [];


    public function getMettingMessageSlack(){
        $slack_mess = 'Đăng ký *buổi họp* ngày '. $this->meeting_date .' lúc '. $this->meeting_time. ', kết thúc lúc '. $this->meeting_end . ' tại phòng họp '. $this->meeting_room;
        return sprintf($slack_mess);
    }
    public function getCompany(){
        return $this->belongsTo(CompanyInfo::class, 'company_id', 'company_info_id');
    }

    public function getMettingMessageSlackPrivate(){
        $slack_mess = 'Đăng ký *buổi họp* ngày '. $this->meeting_date .' lúc '. $this->meeting_time. ', kết thúc lúc '. $this->meeting_end . ' tại phòng họp '. $this->meeting_room. '. Nội dung: '.$this->meeting_title;
        return sprintf($slack_mess);
    }
}

