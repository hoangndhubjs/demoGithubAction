<?php
namespace App\Traits;

trait SlackNotify
{
    public $webhook;

    public function routeNotificationForSlack($notification)
    {
        if($this->webhook){
            return config('notifications.channels.'.$this->webhook);
        }
        //return config('notifications.slack_hook');
    }

    public function slackChannel($channel){
        $this->webhook = $channel;
        return $this;
    }
}