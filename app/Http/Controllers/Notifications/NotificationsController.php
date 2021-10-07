<?php

namespace App\Http\Controllers\Notifications;

use App\Http\Controllers\Controller;
use App\Repositories\MeetingsRepository;
use App\Repositories\NotificationRepository;
use Illuminate\Http\Request;
use App\Traits\DatatableResponseable;
use App\Classes\PaginateConfig;
use Illuminate\Support\Facades\Auth;

class NotificationsController extends Controller
{
    use DatatableResponseable;

    private $notifications;

    public function __construct(NotificationRepository $notifications)
    {
        $this->notifications = $notifications;
    }

    public function index()
    {
        $page_title = __('notification me');
        //$notifications = $this->notifications->getNotificationByUserId(Auth::user()->user_id);
        return view('pages.notification.list', compact('page_title'/*, 'notifications'*/));
    }
    
    /*public function listOfficeShift(Request $request) {
        $paginateConfig = PaginateConfig::fromDatatable($request);
        if (!$paginateConfig->getSortColumn()) {
            $paginateConfig->setSortBy('create_date');
        }
        $officeShift = $this->officeShift->getOfficeShift($paginateConfig);
        return $this->makeDatatableResponse($officeShift, $paginateConfig->getSortColumn(), $paginateConfig->getSortDir());
    }*/

    public function listNotifi(Request $request){
        $paginateConfig = PaginateConfig::fromDatatable($request);
        $notifications = $this->notifications->getNotificationsByUserId($paginateConfig, $request->get('status'));
        return $this->makeDatatableResponse($notifications, $paginateConfig->getSortColumn(), $paginateConfig->getSortDir());
    }

    public function updateNotifi(Request $request){
        $ids = $request->ids;
        if($ids){
            $row = 0;
            foreach ($ids as $id){
                if($notification = $this->notifications->find($id)){
                    $data = array(
                        'is_notify' => 0,
                    );
                    if($notification->update($data)){
                        $row++;
                    }
                }
            }
            if($row > 0){
                return $this->responseSuccess(__('Thành công'));
            } {
                return $this->responseError( __('xin_hrsale_leave_quota_completed'));
            }
        } else {
            return $this->responseError( __('xin_hrsale_leave_quota_completed'));
        }
    }
}
