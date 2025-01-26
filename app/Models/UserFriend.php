<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserFriend extends Model
{
    protected $fillable = ['user_id', 'friend_id'];

    public function scopeIsUserFriend($query, $friend) 
    {
        return $query->where(function ($query) use ($friend){
            $query->where('user_id', authUserId());
            $query->where('friend_id', $friend->id);
        })->orWhere(function ($query) use ($friend){
            $query->where('user_id', $friend->id);
            $query->where('friend_id', authUserId());
        });
    }
}
