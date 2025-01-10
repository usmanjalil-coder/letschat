<?php

use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

function authUserId()
{
    return Auth::check() ? Auth::user()->id : null;
}
function toLocalTimeZone($date)
{
    $date = Carbon::parse($date)->timezone('Asia/Karachi');
    return $date->diffForHumans();
}

function getNotificationCounter() {
    return Auth::check() 
            ? Notification::where('to_user_id', Auth::user()->id)->where('action', 'friend_request')->count() 
            : 0;
}