<?php
namespace App\Repositories;

use App\Models\Leave;
use App\Models\Meeting;
use App\Notifications\SlackNotification;
use App\View\Components\Logo;
use Illuminate\Support\Facades\Auth;

class MeetingsRepository extends Repository
{
    public function getModel(): string
    {
        return Meeting::class;
    }
    public function create(array $attributes)
    {
//        dd($attributes['employee_id']);
        $metting_request = parent::create($attributes);
        //$metting_request->notify(new SlackNotification($metting_request->getMettingMessageSlack()));
        $metting_request->slackChannel('channel_public')->notify(new SlackNotification($metting_request->getMettingMessageSlack()));
        $metting_request->slackChannel('channel_private')->notify(new SlackNotification($metting_request->getMettingMessageSlackPrivate()));
        $modules = array('name'=> 'meetings','id'=>$metting_request->meeting_id);
        $arr_user_id = explode(',', $attributes['employee_id']);
        $notifi = (new NotificationRepository())->add_notification('Họp '.$attributes['meeting_title'], $modules, $arr_user_id);
        return $metting_request;
    }
    public function getMeeting() {
        $user_info = Auth::user();
        if ($user_info->isAdmin()) {
            $meetings = $this->model->with('getCompany')->orderBy('meeting_id','desc')->paginate(20);
        } else {
            $meetings = $this->model->with('getCompany')->orderBy('meeting_id','desc')->whereRaw('FIND_IN_SET('.Auth::id().',employee_id)')->paginate(20);
        }
       return $meetings;
    }

    public function getMeetingByUserRole($startDate, $endDate){
        $data_reponse = [];
        $user_info = Auth::user();
        if ($user_info->isAdmin()) {
            $listMeetings = Meeting::whereBetween('meeting_date', [$startDate, $endDate])->get();
        } else {
            $listMeetings = Meeting::whereBetween('meeting_date', [$startDate, $endDate])->whereRaw('FIND_IN_SET('.Auth::id().',employee_id)')->get();
        }
        foreach ($listMeetings as $meeting_id){
            $date_start = date("H:i", strtotime($meeting_id->meeting_time));
            $date_end = date("H:i", strtotime($meeting_id->meeting_end));
            $data_reponse[] = [
                'id'=>$meeting_id->meeting_id,
                'title' => $date_start.' | '.$meeting_id->meeting_title,
                'start' => $meeting_id->meeting_date.'T'.$date_start,
                'end'   => $meeting_id->meeting_date.'T'.$date_end,
                'description' => $meeting_id->meeting_title,
                'unq'=> 1,
                'className'=> "fc-event-light fc-event-solid-primary",
            ];
        }
//        dd($data_reponse);
        return $data_reponse;
    }
    public function duplicateTime($meeting_time, $meeting_end, $meeting_date){
        $query = $this->model->where('meeting_date', $meeting_date)->where(function ($sub) use ($meeting_end, $meeting_time) {
            $sub->where('meeting_time','>=',$meeting_time)->where('meeting_end','<=',$meeting_end)
                ->orWhere(function ($sub2) use ($meeting_time, $meeting_end){
                  return $sub2->whereBetween('meeting_end', [$meeting_time, $meeting_end]);
                })
                ->orWhere(function ($sub3) use ($meeting_time, $meeting_end){
                    return $sub3->whereBetween('meeting_time', [$meeting_time, $meeting_end]);
                })
                ->orWhere(function ($sub4) use  ($meeting_end, $meeting_time){
                    $sub4->where('meeting_time','<=',$meeting_time)->where('meeting_end','>=',$meeting_end);
                });
        })->get();
        $meetings_in_day = $this->model->where('meeting_date', $meeting_date)->get();
        if(count($query) > 0){
            return $meetings_in_day;
        }else{
            return array();
        }
    }

    public function detail_meetings($meeting_id){
        $query = $this->model->with('getCompany')->where('meeting_id', $meeting_id)->get();

        $data_image = []; $list_user = $query->toArray();
        $string_to_array = explode(',', $list_user[0]['employee_id']);
        foreach ($string_to_array as $image_user){
            $image = (new EmployeeRepository())->getEmployee($image_user);
            $data_image['user_info'][] = [
                'user_name' => $image->last_name.' '.$image->first_name,
                'image_user'=>$image->profile_picture ? $image->profile_picture : config('services.sso.url').'/storage/uploads/images/avatar.png',
            ];
        }
//        logo_1596014132.png
        $data_company = array(
            'logo_company' => "",
            'company_name' => $list_user[0]['get_company']['company_name'],
        );
        array_push($data_image, $data_company);
        return response()->json(array_merge($query->toArray(), $data_image));
    }
}
