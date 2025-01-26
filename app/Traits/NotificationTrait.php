<?php

namespace App\Traits;

use App\Events\NotificationCounter;
use App\Models\Notification;

trait NotificationTrait
{
    /**
     * Create a new class instance.
     */
    public function post_notification($args = [])
    {
        $to_user_id = !isset($args['to_user_id']) ? dd(400) : $args['to_user_id'];
        $from_user_id = !isset($args['from_user_id']) ? authUserId() : $args['from_user_id'];
        $action = !isset($args['action']) ? dd(400) : $args['action'];
        $node_type = !isset($args['node_type']) ? '' : $args['node_type'];
        $node_url = !isset($args['node_url']) ? '' : $args['node_url'];
        $message = !isset($args['message']) ? '' : $args['message'];
        $notify_id = !isset($args['notify_id']) ? '' : $args['notify_id'];
        $notifiable_type = !isset($args['notifiable_type']) ? null : $args['notifiable_type'];
        $notifiable_id = !isset($args['notifiable_id']) ? null : $args['notifiable_id'];

        
        $action != 'request_cancel' && Notification::create([
            'to_user_id' => $to_user_id,
            'from_user_id' => $from_user_id,
            'action' => $action,
            'node_type' => $node_type,
            'node_url' => $node_url,
            'notify_id' => $notify_id,
            'message' => $message,
            'notifiable_type' => $notifiable_type,
            'notifiable_id' => $notifiable_id,
        ]);
        $user_with_message = \App\Models\User::whereId($from_user_id)->get()->map(function($user) use ($message, $action) {
            $user['message'] = $message;
            $user['message_type'] = $action;
            return $user;
        });

        broadcast(new NotificationCounter($to_user_id, $user_with_message ));
    }
}
