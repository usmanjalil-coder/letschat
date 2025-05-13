<?php

use Carbon\Carbon;
use App\Models\Message;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

if (!function_exists('authUserId')) {
    function authUserId()
    {
        return Auth::check() ? Auth::user()->id : null;
    }
}

if (!function_exists('toLocalTimeZone')) {
    function toLocalTimeZone($date)
    {
        $date = Carbon::parse($date)->timezone('Asia/Karachi');
        return $date->diffForHumans();
    }
}
if (!function_exists('getNotificationCounter')) {
    function getNotificationCounter()
    {
        $actions = ['friend_request', 'request_accepted'];
        return Auth::check()
            ? Notification::where('to_user_id', Auth::user()->id)
                ->where('read_at', null)
                ->whereIn('action', $actions)->count()
            : 0;
    }

}

if (!function_exists('getLocalTimeZoneForMessages')) {
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

}
if (!function_exists('thumbnail')) {
    function thumbnail($path)
    {
        $thumbnailPath = Str::replaceLast('.', '_thumbnail.', $path);
        $img = \Image::make(public_path('/') . $path);
    
        [$width, $height] = getimagesize(public_path('/') . $path);
    
        $img->orientate();
    
        $imgSize = $img->filesize() / 1024;
        if ($imgSize <= 100) {
            $percent = 1;
            $quality = 70;
        } elseif ($imgSize <= 200) {
            $percent = 1.1;
            $quality = 70;
        } elseif ($imgSize <= 400) {
            $percent = 1.2;
            $quality = 65;
        } elseif ($imgSize <= 800) {
            $percent = 2;
            $quality = 50;
        } elseif ($imgSize <= 1024) {
            $percent = 2.1;
            $quality = 55;
        } elseif ($imgSize <= 2048) {
            $percent = 3;
            $quality = 50;
        } elseif ($imgSize <= 4096) {
            $percent = 5;
            $quality = 45;
        } else {
            $quality = 40;
            $percent = 7;
        }
    
        $final_width = floor(($width / $percent));
    
        $img->resize((int)$final_width, null, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });
    
        return $img->save($thumbnailPath, $quality, 'jpg');
    }

}

if(!function_exists('getLastSeen')) {
    function getLastSeen($date) {

        try {
            $time = Carbon::parse($date)->timezone('Asia/Karachi');
            $diffInDays = $time->diffInDays(now());
            $hours = $time->diffInHours();
            $minutes = $time->diffInMinutes();
            if ($time->year < 1900 || $time->year > now()->year + 1) {
                return null;
            } else {
                if ($minutes < 1) return number_format($minutes).'Just now';
                elseif ($minutes > 1 && $minutes < 60) return number_format($minutes).'m ago';
                elseif ($hours > 1 && $hours < 24) return number_format($hours).'h ago';
                elseif ($diffInDays > 1 && $diffInDay < 2) {
                    return 'Yesterday at'. $time->format('g:i A');
                }
                elseif($diffInDays > 7) return '1w ago';
                elseif($diffInDays > 14) return '2w ago';
                elseif($diffInDays > 21) return '3w ago';
                // elseif($diffInDays > 21) return '3w ago';
                elseif($diffInDays > 30 && $diffInDays < 40) return '1month ago';
                else return $time->format('d/m/Y \a\t g:i A');
            }
        } catch (\Exception $e) {
            return null;
        }
    }
}

if(!function_exists('getNotificationCounterForMessage')) {
    function getNotificationCounterForMessage($friend_id) 
    {
        return Message::where('sender_id', $friend_id)->where('receiver_id', auth()->user()->id)->where('status', 'sent')->count();
    }
}
if(!function_exists('getNotificationCounterForBroad')) {
    function getNotificationCounterForBroad($friend_id) 
    {
        return Message::where('sender_id', authUserId())->where('receiver_id', $friend_id)->where('status', 'sent')->count();
    }
}