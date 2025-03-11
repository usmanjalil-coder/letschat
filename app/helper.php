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

function getNotificationCounter()
{
    $actions = ['friend_request', 'request_accepted'];
    return Auth::check()
        ? Notification::where('to_user_id', Auth::user()->id)
            ->where('read_at', null)
            ->whereIn('action', $actions)->count()
        : 0;
}

function getLocalTimeZoneForMessages($dt)
{
    $time = Carbon::parse($dt)->timezone('Asia/Karachi');
    if ($time->diffInMinutes() < 60) {
        $timeIs = $time->diffForHumans();
    } else {
        if ($time->isToday()) {
            $timeIs = 'Today at' . $time->format('h:i A');
        } else if ($time->isYesterday()) {
            $timeIs = 'Yesterday at' . $time->format('h:i');
        } else {
            $timeIs = $time->format('h:i');
        }
    }
    return $timeIs;
}
