<?php
namespace App\Classes;

use App\Repositories\NotificationRepository;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;

class Notification
{

    public static function totalNotification() {
        $total = app()->make(NotificationRepository::class)->totalNotificationByUserId(Auth::user()->user_id);
        return $total;
    }

    public static function listNotifications(){
        $notifications = app()->make(NotificationRepository::class)->listNotificationNewById(Auth::user()->user_id);
        return $notifications;
    }
}