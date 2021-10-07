<?php
namespace App\Models;

use App\Notifications\SlackNotification;
use App\Repositories\NotificationRepository;
use App\Traits\SlackNotify;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;

class Compensations extends CacheModel
{
    use Notifiable, SlackNotify;
    protected $table = 'compensations';
    protected $primaryKey = 'compensation_id';
    protected $guarded = [];

    public function getCompensationsMessageSlack($title){
        return sprintf($title);
    }
    public function employee() {
        return $this->belongsTo(Employee::class, 'employee_id', 'user_id');
    }
}
