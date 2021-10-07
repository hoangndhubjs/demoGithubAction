<?php

namespace App\Models;

use App\Traits\SlackNotify;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;

class Leave extends CacheModel
{
    use Notifiable, SlackNotify;

    protected $table = 'leave_applications';
    protected $appends = ['leave_types','salary','confirm_list','time_detail'];

    protected $primaryKey = 'leave_id';
    protected $guarded = [];

    const STATUS_PENDING = 1;
    const STATUS_CONFIRMED = 2;
    const STATUS_REJECT = 3;

    const CONFIRM_PENDING = 0;
    const CONFIRM_APPROVE = 1;

    const IS_SALARY = 1;
    const NOT_SALARY = 0;

    const LEAVE_ALL_DAY = 1;
    const LEAVE_HALF_DAY = 2;
    const LEAVE_IN_DAY = 3;

    const HALF_DAY_MORNING = 0;
    const HALF_DAY_AFTERNOON = 1;

    const LEAVE_TYPE_PHEP_TON = 5;
    const LEAVE_TYPE_CONG_TAC = 6;

    const STATUS_LABELS = [
        self::STATUS_PENDING => 'Đang chờ xử lý',
        self::STATUS_CONFIRMED => 'Đã phê duyệt',
        self::STATUS_REJECT => 'Từ chối',
    ];

    const CONFIRM_LABELS = [
        self::CONFIRM_PENDING => 'Chưa xác nhận',
        self::CONFIRM_APPROVE => 'Xác nhận',
    ];

    const SALARY_LABELS = [
        self::NOT_SALARY => 'Không lương',
        self::IS_SALARY => 'Có lương',
    ];

    const LEAVE_TIME_TYPES = [
        self::LEAVE_ALL_DAY => 'Nghỉ cả ngày',
        self::LEAVE_HALF_DAY => 'Nghỉ nửa ngày',
        self::LEAVE_IN_DAY => 'Nghỉ trong ngày',
    ];

    public function isSalary() {
        return $this->is_salary == self::IS_SALARY;
    }

    public function leaveHalfDayMorning(){
        return $this->type_half_day == self::HALF_DAY_MORNING;
    }

    public function leaveHalfDayAfternoon(){
        return $this->type_half_day == self::HALF_DAY_AFTERNOON;
    }

    public function getNotifyMessage() {
        $full_name = Auth::user()->first_name.' '.Auth::user()->last_name;
        $notification = '';
        if($this->leave_time_types == self::LEAVE_ALL_DAY){
            if($this->from_date == $this->to_date){
                $notification = $full_name. ' xin nghỉ ngày '. $this->from_date;
            } else {
                $notification = $full_name. ' xin nghỉ từ ngày '. $this->from_date.' tới ngày '.$this->to_date;
            }
        } else if ($this->leave_time_types == self::LEAVE_HALF_DAY){
            $type_half_day = (self::leaveHalfDayMorning())?'buổi sáng':'buổi chiều';
            $notification = $full_name. ' xin nghỉ ' .$type_half_day. ' ngày '. $this->from_date;
        } else if($this->leave_time_types == self::LEAVE_IN_DAY){
            $notification = $full_name . ' xin về sớm 1h từ '.$this->from_time.' tới '.$this->to_time.' ngày '. $this->from_date;
        }
        return sprintf($notification);
    }

    public function getNotifyMessageSlackPrivate(){
        $full_name = Auth::user()->first_name.' '.Auth::user()->last_name;
        $notification = '';
        if($this->leave_time_types == self::LEAVE_ALL_DAY){
            if($this->from_date == $this->to_date){
                $notification = $full_name. ' xin nghỉ ngày '. $this->from_date;
            } else {
                $notification = $full_name. ' xin nghỉ từ ngày '. $this->from_date.' tới ngày '.$this->to_date;
            }
        } else if ($this->leave_time_types == self::LEAVE_HALF_DAY){
            $type_half_day = (self::leaveHalfDayMorning())?'buổi sáng':'buổi chiều';
            $notification = $full_name. ' xin nghỉ ' .$type_half_day. ' ngày '. $this->from_date;
        } else if($this->leave_time_types == self::LEAVE_IN_DAY){
            $notification = $full_name . ' xin về sớm 1h từ '.$this->from_time.' tới '.$this->to_time.' ngày '. $this->from_date;
        }
        $notification .= '. Lý do: '.$this->reason;
        return sprintf($notification);
    }

    public function employee() {
        return $this->belongsTo(Employee::class, 'employee_id', 'user_id');
    }

    public function leave_type() {
        return $this->belongsTo(LeaveType::class, 'leave_type_id', 'leave_type_id');
    }

    public function getLeaveTypesAttribute() {
        return __(self::LEAVE_TIME_TYPES[$this->leave_time_types]);
    }

    public function getSalaryAttribute() {
        return __(self::SALARY_LABELS[$this->is_salary]);
    }

    public function getConfirmListAttribute(){
        if($this->status == self::STATUS_PENDING){
            return __('Leader chưa duyệt');
        } elseif($this->status == self::STATUS_REJECT){
            return __('Từ chối');
        } else {
            return __(self::CONFIRM_LABELS[$this->confirm]);
        }
    }

    public function getTimeDetailAttribute(){
        $duration = '';
        if($this->leave_time_types == self::LEAVE_ALL_DAY){
            if($this->from_date == $this->to_date){
                $duration = 'Ngày <span class=" text-primary">'. $this->from_date.' </span>';
            } else {
                $duration = 'Từ ngày <span class=" text-primary">'. $this->from_date.' </span><br/>tới ngày <span class=" text-primary">'.$this->to_date.'</span>';
            }
        } else if ($this->leave_time_types == self::LEAVE_HALF_DAY){
            $type_half_day = (self::leaveHalfDayMorning())?'<span class=" text-primary">Buổi sáng</span>':'<span class=" text-primary">Buổi chiều</span>';
            $duration = $type_half_day. '<br/> ngày <span class=" text-primary">'. $this->from_date.'</span>';
        } else if($this->leave_time_types == self::LEAVE_IN_DAY){
            $str_start_time = strtotime($this->from_time);
            $str_end_time = strtotime($this->to_time);
            $totalTime = ($str_end_time - $str_start_time)/3600;
            if($str_start_time <= strtotime('11:30am') && $str_end_time >= strtotime('1:30pm')){
                $totalTime = ($str_end_time - $str_start_time - 5400)/3600;
            }
            if(strtotime($this->from_time)>= strtotime('5:00pm')){
                $tring = 'Về sớm '.$totalTime .'h';
            } else if(strtotime($this->to_time) <= strtotime('9:30am')){
                $tring = 'Đến muộn '.$totalTime .'h';
            } else {
                $tring = 'Nghỉ giữa giờ '.$totalTime .'h';
            }
            /*$tring = 'Về sớm 1h';*/

            $duration = '<span class=" text-primary">'.$tring.'</span><br/> từ <span class=" text-primary">'.$this->from_time.'</span> tới <span class="text-primary">'.$this->to_time.'</span> <br/> ngày <span class="text-primary">'. $this->from_date.'</span>';
        }

        return $duration;
    }
    public function companyLeave() {
        return $this->belongsTo(CompanyInfo::class, 'company_id', 'company_info_id');
    }

}
