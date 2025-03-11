<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\DatabaseNotification;

class Notification extends DatabaseNotification
{
    use HasFactory;

    public function scopeFriendRequest($query)
    {
        return $query->where('from_user_id', '=', authUserId())->where('action', '=', 'friend_request');
    }

    public static function allRead($to_user_id)
    {
        return static::where('to_user_id', authUserId())
                    ->where('from_user_id', $to_user_id)
                    ->where('action', 'message')
                    ->update(['read_at' => now()]);
    }
}
