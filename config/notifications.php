<?php
return [
    'slack_hook' => env('SLACK_HOOK', 'test'),
    'channels' => [
        'channel_public' => env('SLACK_HOOK', 'test'),
        'channel_private' => env('SLACK_HOOK_PRIVATE', 'test')
    ],
    'EMPLOYEES_RECEIVE_NOTIFICATION' => env('EMPLOYEES_RECEIVE_NOTIFICATION', null),
];
?>