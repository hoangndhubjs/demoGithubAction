<?php
namespace App\Repositories;

use App\Models\HrsaleNotificaion;
use Illuminate\Support\Facades\Auth;
use Pusher\Pusher;

class NotificationRepository extends Repository
{
    public function getModel(): string
    {
        return HrsaleNotificaion::class;
    }

    /**
     * @param $title
     * @param $module
     * @param $array_user_id
     * @return bool
     * @throws \Pusher\PusherException
     */
    public function add_notification($title, $module, $array_user_id) {

        foreach($array_user_id as $user_id) {
            $data = array(
                'module_name' => $module['name'],
                'title'		=> $title,
                'module_id' => $module['id'],
                'employee_id' => $user_id,
                'is_notify' => '1',
            );
            $this->model->create($data);
            $this->sendNotification($user_id, $title);
        }
        return true;
    }

    /**
     * send notification
     *
     * @param $user_id
     * @param $title
     * @throws \Pusher\PusherException
     *
     */
    public function sendNotification($user_id, $title){
        $options = array(
            'cluster' => config('broadcasting.connections.pusher.options.cluster'),
        );
        $pusher = new Pusher(
            config('broadcasting.connections.pusher.key'),
            config('broadcasting.connections.pusher.secret'),
            config('broadcasting.connections.pusher.app_id'),
            $options
        );
        $pusher->trigger('nhansu.hqgroups_'.$user_id, 'nhansu.hqgroups', array('message'=>$title));
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getNotificationByUserId($id) {
        $result = $this->model->where('employee_id', $id)->orderBy('created_at', 'desc')->paginate(20);
        return $result;
    }

    /**
     * Tổng tin mới
     *
     * @param $id
     * @return mixed
     */
    public function totalNotificationByUserId($id){
        $total = $this->model->where('employee_id', $id)->where('is_notify', 1)->count('notificaion_id');
        return $total;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function listNotificationNewById($id){
        $result = $this->model->where('employee_id', $id)->orderBy('created_at', 'desc')->limit(5)->get();
        return $result;
    }

    /**
     * @param $data
     * @param $id
     * @param $employee_id
     * @param $module_name
     * @return bool
     */
    public function updateNotificationRecord($data, $id,$employee_id,$module_name){
        $query = $this->model->where('module_id', $id)
            ->where('employee_id', $employee_id)
            ->where('module_name', $module_name);
        if($query->update($data)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param $paginateConfig
     * @param null $status
     * @param null $user_id
     * @return mixed
     */
    public function getNotificationsByUserId($paginateConfig, $status = null, $user_id = null){
        if(!$user_id){
            $user_id = Auth::id();
        }
        $query = $this->model->where('employee_id', $user_id);
        if ($type = request()->get('type')) {
            $query->where('module_name', $type);
        }

        if ($is_notify = request()->get('is_notify')) {
            $query->where('is_notify', $is_notify);
        }

        if ($column = $paginateConfig->getSortColumn()) {
            $query->orderBy($column, $paginateConfig->getSortDir());
        }
        $notifications = $query->paginate($paginateConfig->getPerPage(), $paginateConfig->getColumns(), 'page', $paginateConfig->getPage());
        foreach($notifications as $notification) {
            if($notification->module_name == 'leave'){
                $url = route('leaves.detail', ['id' => $notification->module_id]);
                $notification->url = $url;
            } else if($notification->module_name == 'request_ot') {
                $url = route('overtime_request.detail', ['id' => $notification->module_id]);
                $notification->url = $url;
            } else if ($notification->module_name == 'compensations'){
                $url = route('compensations.detail', ['id' => $notification->module_id]);
                $notification->url = $url;
            }
        }
        return $notifications;
    }

}
