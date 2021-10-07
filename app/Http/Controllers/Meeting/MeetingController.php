<?php

namespace App\Http\Controllers\Meeting;

use App\Classes\PaginateConfig;
use App\Http\Controllers\Controller;
use App\Repositories\MeetingsRepository;
use App\Repositories\NotificationRepository;
use App\Traits\DatatableResponseable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MeetingController extends Controller
{
    use DatatableResponseable;

    private $meeting;

    public function __construct(MeetingsRepository $meeting)
    {
        $this->meeting = $meeting;
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function detail($id){
        if($this->meeting->find($id)){
            $edata = array(
                'is_notify' => 0,
            );
            $type = (new NotificationRepository())->updateNotificationRecord($edata, $id, Auth::user()->user_id,'meetings');
        }
        $meetings = $this->meeting->detail_meetings($id);
        return $meetings;
    }
    public function list(){

        $list_meetings = $this->meeting->getMeeting();
        return view('pages.notification.list_meetings', compact('list_meetings'));
    }
}
