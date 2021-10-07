<?php
namespace App\Repositories;

use App\Models\Leave;
use App\Models\LeaveType;
use App\Notifications\SlackNotification;
use App\Notifications\TestNotification;
use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Auth;
use Lang;
use Illuminate\Support\Facades\DB;
use Pusher\Pusher;
use Illuminate\Support\Str;
use DateTime;

class LeaveRepository extends Repository
{
    const END_MORNING = '12:00pm';
    const START_AFTERNOON = '1:30pm';

    public function getModel(): string
    {
        return Leave::class;
    }

    public function create(array $attributes)
    {
        $leave_request = parent::create($attributes);
        //$leave_request->notify(new SlackNotification($leave_request->getNotifyMessage()));
        $leave_request->slackChannel('channel_public')->notify(new SlackNotification($leave_request->getNotifyMessage()));
        $leave_request->slackChannel('channel_private')->notify(new SlackNotification($leave_request->getNotifyMessageSlackPrivate()));
        $title = Auth::user()->first_name. ' '.Auth::user()->last_name. ' xin nghỉ';
        $module = array('name'=>'leave','id'=>$leave_request->leave_id);
        $array_user_id = (config('notifications.EMPLOYEES_RECEIVE_NOTIFICATION'))?explode(',', config('notifications.EMPLOYEES_RECEIVE_NOTIFICATION')):array();
        if(Auth::user()->reports_to) {
            $array_user_id[] = Auth::user()->reports_to;
        }
        $notifi = (new NotificationRepository())->add_notification($title, $module, $array_user_id);
        return $leave_request;
    }

    /**
     * get leave by user id
     *
     * @param $paginateConfig
     * @param null $from
     * @param null $to
     * @param null $user_id
     * @return mixed
     */
    public function getLeavesByUserId($paginateConfig, $from = null, $to = null, $user_id = null) {
        if(!$user_id){
            $user_id = Auth::id();
        }
        $query = $this->model->with(['employee', 'leave_type'])
            ->where('employee_id', $user_id)
            ->where('company_id', Auth::user()->company_id);
        if($from) {
            $query->where('create_date', '>=', $from);
        }
        if ($to) {
            $query->where('create_date', '<=', $from);
        }
        if ($column = $paginateConfig->getSortColumn()) {
            $query->orderBy($column, $paginateConfig->getSortDir());
        }
        $leaves = $query->paginate($paginateConfig->getPerPage(), $paginateConfig->getColumns(), 'page', $paginateConfig->getPage());

        return $leaves;
    }
    public function getLeaveByUserRole($startDate, $endDate)
    {
        $user_info = Auth::user();

        if ($user_info->isAdmin()) {
            $query = Leave::with('employee')->whereBetween('from_date', [$startDate, $endDate])->orWhereBetween('to_date', [$startDate, $endDate])->get();
        } else {
            $query = Leave::with('employee')->where('employee_id', $user_info->user_id)->whereNested(function (Builder $sub) use($startDate, $endDate) {
                return $sub->whereBetween('from_date', [$startDate, $endDate])->orWhereBetween('to_date', [$startDate, $endDate]);
            })->get();
        }

        $data_reponse = [];

        foreach ($query as $leave_id) {
            // count day
            $startTimeStamp = strtotime($leave_id->from_date);
            $endTimeStamp = strtotime($leave_id->to_date);
            $timeDiff = abs($endTimeStamp - $startTimeStamp);
            $numberDays = $timeDiff/86400;
            $numberDays = intval($numberDays);
            $date = new DateTime($leave_id->to_date);

            $date_formart = $date->modify('+1 day');
            $dates = $date_formart->format('Y-m-d');
            $endDate = $numberDays >= 1 ? $dates : $leave_id->to_date;

            $name = $leave_id->employee['last_name'] . ' ' . $leave_id->employee['first_name'];
            $data_reponse[] = [
                'leave_id' => $leave_id->leave_id,
                'title' => Str::limit($leave_id->reason, 100) . ' ' . __('xin_hr_calendar_lv_request_by') . ' ' . $name,
                'start' => $leave_id->from_date,
                'end' => $endDate,
                'description' => Str::limit($leave_id->reason, 100) . ' ' . __('xin_hr_calendar_lv_request_by') . ' ' . $name,
                'unq' => 10,
                'className' => "fc-end fc-event-light fc-event-solid-danger",
            ];
        }
        return $data_reponse;
    }
    /**
     * Tính thời gian nghỉ ngày
     *
     * @param $start_date
     * @param $start_time
     * @param $end_time
     * @return float|int
     */
    public function noOfDays($start_date, $start_time, $end_time){
        $str_start_time = strtotime($start_time);
        $str_end_time = strtotime($end_time);
        $totalTime = ($str_end_time - $str_start_time)/3600;
        if($str_start_time <= strtotime(self::END_MORNING) && $str_end_time >= strtotime(self::START_AFTERNOON)){
            $totalTime = (($str_end_time - $str_start_time) - (strtotime(self::START_AFTERNOON) - strtotime(self::END_MORNING)))/3600;
        }

        $office_shift = (new OfficeShiftRepository())->find(Auth::user()->office_shift_id);
        $shift = strtolower(date('l', strtotime($start_date)));
        $in = $shift.'_in_time';
        $out = $shift.'_out_time';
        $office_shift_start = $office_shift->$in;
        $office_shift_end = $office_shift->$out;
        $time_shift = 8;
        if(isset($office_shift_start) && isset($office_shift_end)){
            $time_shift = (strtotime($office_shift_end)-strtotime($office_shift_start)-5400)/3600;
            if(strtotime($office_shift_start) >= strtotime('13:30') || strtotime($office_shift_end) <= strtotime('12:00')) {
                $time_shift = (strtotime($office_shift_end)-strtotime($office_shift_start))/3600;
            }
        }
        if($totalTime > 1){
            $no_of_days = 0;
        } else {
            $no_of_days = $totalTime/$time_shift;
        }
        return $no_of_days;
    }

    /**
     * Lấy tổng số đi sớm về muộn trong tháng của user
     *
     * @param $date
     * @param $employee_id
     * @return mixedư
     */

    public function getLeavesInMonthByEmployeeId($date, $employee_id){
        $first_day = date('Y-m-01', strtotime($date));
        $last_day = date('Y-m-t', strtotime($date));

        $query = $this->model->where('from_date', '>=', $first_day)
                            ->where('from_date', '<=' , $last_day)
                            ->where('leave_time_types', 3)
                            ->where('status', 2)
                            ->where('confirm', 1)
                            ->where('employee_id', $employee_id)->count('leave_id');

        return $query;
    }

    /**
     * tính số phép còn lại của user theo loại nghỉ
     *
     * @param $leave_type_id
     * @param $employee_id
     * @return int
     */
    public function getLeaveRemainingTotal($leave_type_id, $employee_id){
        $remaining_leave = $this->count_total_day_leaves($leave_type_id, $employee_id);

        $type = (new LeaveTypeRepository())->find($leave_type_id);
        if (!is_null($type)) {
            $total = $type->days_per_year;
            $leave_remaining_total = $total - $remaining_leave;
        } else {
            $leave_remaining_total = 0;
        }

        return $leave_remaining_total;
    }

    /**
     * tính tổng số ngày phép đã nghỉ của nhân viên theo loại
     *
     * @param $leave_type_id
     * @param $employee_id
     * @return mixed
     */
    public function count_total_day_leaves($leave_type_id, $employee_id){
        $query = $this->model->where('status', 2)
            ->where('confirm', 1)
            ->where('employee_id', $employee_id)
            ->where('leave_type_id',$leave_type_id)
            ->whereIn('leave_time_types', [1,2])
            ->sum('total_day_leave');
        return $query;
    }

    public function getApprovedLeaveApplicationsOf($employee_id, $date)
    {
        return $this->model->where([
            'confirm' => 1,
            'status' => 2,
            'employee_id' => $employee_id
        ])->where('from_date', '<=', $date)
            ->where('to_date', '>=', $date)->get();
    }

    /**
     * kiểm tra số lần nghỉ trong 1 khoảng thời gian
     *
     * @param $leave_type_id
     * @param $employee_id
     * @param $first_day
     * @param $last_day
     * @return mixed
     */
    public function count_day_leaves_by_date($leave_type_id, $employee_id, $first_day, $last_day){
        /*$first_day = date('Y-m-01', strtotime($date));
        $last_day = date('Y-m-t', strtotime($date));*/

        $query = $this->model->where('from_date', '>=', $first_day)
            ->where('from_date', '<=' , $last_day)
            ->where('leave_type_id',$leave_type_id)
            ->where('status', 2)
            ->where('confirm', 1)
            ->where('employee_id', $employee_id)
            ->count('leave_id');

        return $query;
    }

    /**
     * @param $paginateConfig
     * @param null $from
     * @param null $to
     * @return mixed
     */
    public function getLeavesByAdmin($paginateConfig, $request){
        $query = $this->model->with(['employee', 'leave_type']);

        if(isset($request->company_id) && $request->company_id != null) {
            $query->where('company_id', $request->company_id);
        }
        if(isset($request->department_id) && $request->department_id != null) {
            $query->where('department_id', $request->department_id);
        }
        if(isset($request->is_salary) && $request->is_salary != null) {
            $query->where('is_salary', $request->is_salary);
        }

        if(isset($request->status) && $request->status != null) {
            $confirm = [3=>0, 4 => 1];
            $status = [1=>1, 2 => 3];
            if($request->status == 3 || $request->status == 4){
                $query->where('confirm', $confirm[$request->status])
                        ->where('status', 2);
            } else {
                $query->where('status', $status[$request->status]);
            }
        }

        if(isset($request->employee) && $request->employee != null) {
            $query->whereHas('employee', function($q) use ($request){
                $q->where('employee_id', 'LIKE', '%'.$request->employee.'%');
                $q->orWhere(DB::raw("CONCAT(first_name,' ',last_name)"), 'LIKE', '%' . $request->employee . '%');
            });
            /*$query->where(function($q) use ($request) {

                $q->where('employee.employee_id', 'LIKE', '%'.$request->employee.'%');
            });*/
        }

        if(isset($request->created_at) && $request->created_at != null) {
            //$query->wherebetween('created_at', [date('Y-m-d 00:00:00', strtotime($request->created_at)), date('Y-m-d 23:59:59', strtotime($request->created_at))]);
            $query->where(function ($query) use ($request) {
                $query->where('from_date', '<=', date('Y-m-d', strtotime($request->created_at)));
                $query->where('to_date', '>=', date('Y-m-d', strtotime($request->created_at)));
            });
        }

        $query->orderBy('confirm', 'ASC')->orderByRaw('FIELD (status, 2, 1, 3 )')->orderBy('created_at', 'DESC');

        if ($column = $paginateConfig->getSortColumn()) {
            $query->orderBy($column, $paginateConfig->getSortDir());
        }

        $leaves = $query->paginate($paginateConfig->getPerPage(), $paginateConfig->getColumns(), 'page', $paginateConfig->getPage());

        return $leaves;
    }

    public function getLeavesByLeader($paginateConfig, $request){
        $query = $this->model->with(['employee', 'leave_type']);
        $query->whereHas('employee', function($q) {
            $q->where('reports_to', Auth::id());
        });

        if(isset($request->company_id) && $request->company_id != null) {
            $query->where('company_id', $request->company_id);
        }
        if(isset($request->department_id) && $request->department_id != null) {
            $query->where('department_id', $request->department_id);
        }
        if(isset($request->is_salary) && $request->is_salary != null) {
            $query->where('is_salary', $request->is_salary);
        }

        if(isset($request->status) && $request->status != null) {
            $confirm = [3=>0, 4 => 1];
            $status = [1=>1, 2 => 3];
            if($request->status == 3 || $request->status == 4){
                $query->where('confirm', $confirm[$request->status])
                    ->where('status', 2);
            } else {
                $query->where('status', $status[$request->status]);
            }
        }

        if(isset($request->employee) && $request->employee != null) {
            $query->whereHas('employee', function($q) use ($request){
                $q->where('employee_id', 'LIKE', '%'.$request->employee.'%');
                $q->orWhere(DB::raw("CONCAT(first_name,' ',last_name)"), 'LIKE', '%' . $request->employee . '%');
            });
        }

        if(isset($request->created_at) && $request->created_at != null) {
            //$query->wherebetween('created_at', [date('Y-m-d 00:00:00', strtotime($request->created_at)), date('Y-m-d 23:59:59', strtotime($request->created_at))]);
            $query->where(function ($query) use ($request) {
                $query->where('from_date', '<=', date('Y-m-d', strtotime($request->created_at)));
                $query->where('to_date', '>=', date('Y-m-d', strtotime($request->created_at)));
            });
        }

        $query->orderByRaw('FIELD (status, 1, 3, 2 )')->orderBy('confirm', 'ASC')->orderBy('created_at', 'DESC');

        if ($column = $paginateConfig->getSortColumn()) {
            $query->orderBy($column, $paginateConfig->getSortDir());
        }

        $leaves = $query->paginate($paginateConfig->getPerPage(), $paginateConfig->getColumns(), 'page', $paginateConfig->getPage());

        return $leaves;
    }
    /**
     * kiểm tra ngày đơn xin nghỉ => bù công
     * @param $employee_id
     * @param $date
     * @return bool
     */
    public function leaveRequestCompensation($employee_id, $request){
        $date = $request->date;
        $check_date_between = $this->model->where([
            'employee_id'=> $employee_id,
            'status' => 2,
            'confirm' => 1
        ])->where('from_date' , '<', $date)->where('to_date' , '>', $date)->first();
        if ($check_date_between != null){
            $leave_check = $check_date_between;
        }else{
            if($request->status == 2 || $request->status == 3){
                $leave_time_type = 2;
                $type_half_day = $request->status == 3 ? 1 : '';
            }else{
                $leave_time_type = 1;
                $type_half_day = '';
            }
            $leave = $this->model->where([
                'employee_id'=> $employee_id,
                'status' => 2,
                'confirm' => 1,
//                'leave_time_types' => $leave_time_type,
//                'type_half_day' => $type_half_day
            ])->whereDate('from_date', $date)->first();
            $leave_check = $leave;
//            dd($leave_check);
        }
        $check = $this->_leave_type_user($leave_check, $date, $request->status);
        return $check;
    }
    /**
     * kiểm tra trạng thái xin nghỉ
     * @param $employee_id
     * @param $date 'Ngày cần bù công
     * status => trạng thái yêu cầu bù công
     * @return array
     */
    protected function _leave_type_user($leave_request, $date, int $status){
//        $leave_request = $this->leave->leaveRequestCompensation($user_id,$date);
        if (!isset($leave_request)){
            return array();
        }
        $flag_type = array();
        $date = Carbon::createFromFormat('Y-m-d', $date);
        $date = $date->format('d-m-Y');
        $from_date = strtotime($leave_request->from_date);
        $to_date   = strtotime($leave_request->to_date);
        $date_compensation = strtotime($date);

        $type_half_leave = array(
            1 => 'Bạn đã xin nghỉ cả ngày: '.$date,
            2 => 'Bạn đã xin nghỉ buổi sáng: '.$date,
            3 => 'Bạn đã xin nghỉ buổi chiều: '.$date,
        );
        $leave_time_type = $leave_request->leave_time_types;

        if ($status == 1 && $leave_time_type == 2){
            $type_half = $leave_request->type_half_day ? intval($leave_request->type_half_day) : 0;
            $status = $type_half == 1 ? 3 : 2;
            // trả lại khi user chọn đủ công mà có request xin nghỉ nửa ngày
            $flag_type[] = [
                'status' => false,
                'message' => $type_half_leave[$status]
            ];
            return $flag_type;
        }


        if($from_date == $to_date){
            if($leave_time_type == 1 && ($status == 1 || $status == 2 || $status == 3)){
                $flag_type[] = [
                    'status' => false,
                    'message' => 'Bạn đã xin nghỉ cả ngày: '.$date,
                ];
            }elseif($leave_time_type == 2){
                $type_half = $leave_request->type_half_day ? intval($leave_request->type_half_day) : 0;

                if($status == 2 && $type_half != 0){
                    return array();
                }elseif($status == 3 && $type_half  != 1){
                    return array();
                }
//                dd($status , $leave_request->type_half_day);
                $status = $type_half == 1 ? 3 : 2;

                    $flag_type[] = [
                        'status' => false,
                        'message' => $type_half_leave[$status]
                    ];

            }
        }elseif($date_compensation <= $to_date && $date_compensation >= $from_date){
            $flag_type[] = [
                'status' => false,
                'message' => 'Ngày bạn chọn nằm trong khoảng thời gian xin nghỉ'
            ];
        }
        return $flag_type;
    }

    public function checkExistLeave($employee_id, $start_date, $end_date, $type, $type_half_day = null, $leave_id = null){
        $leave = $this->model->where('employee_id', $employee_id)
            ->where('status', '!=', 3)
            ->where(function($query) use ($start_date, $end_date) {
                $query->orWhere([
                        ['from_date', '=<', $start_date],
                        ['to_date', '>=', $end_date],
                    ])
                    ->orWhereBetween('from_date',[$start_date, $end_date])
                    ->orWhereBetween('to_date', [$start_date, $end_date]);
            });
        if(isset($leave_id)){
           $leave->where('leave_id', '!=', $leave_id);
        }
        $leave = $leave->first();

        if($leave){
            if($leave->leave_time_types == Leave::LEAVE_ALL_DAY){
                return false;
            } elseif($leave->leave_time_types == Leave::LEAVE_HALF_DAY){
                if($type == Leave::LEAVE_ALL_DAY){
                    return false;
                } elseif($type == Leave::LEAVE_HALF_DAY){
                    if($leave->type_half_day == $type_half_day){
                        return false;
                    }
                } elseif($type == Leave::LEAVE_IN_DAY) {
                    if($leave->type_half_day == 1){
                        return false;
                    }
                }
            }
        }

        return true;
    }
}
