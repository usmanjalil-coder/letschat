<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'message',
        'images',
        'message_type',
        'audio_file_name',
        'audio_file_path'
    ];


    public function sender(){
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver(){
        return $this->belongsTo(User::class,'receiver_id');
    }

    protected function createdAt(): Attribute
    {
        return new Attribute(
            get: function ($value) {
                $time = Carbon::parse($value)->timezone('Asia/Karachi');
                if($time->diffInSeconds() < 60) return 'Just now';

                return $time->diffInMinutes() < 60 ? $time->diffForHumans() : $time->format('g:i A');
            },
        );
    }

    public static function scopeUsersMessage($query, $userId, $receiverId)
    {
        $query->where(function ($query) use ($userId, $receiverId) {
            $query->where('sender_id', $userId)
                ->where('receiver_id', $receiverId);
        })->orWhere(function ($query) use ($userId, $receiverId) {
            $query->where('sender_id', $receiverId)
                ->where('receiver_id', $userId);
        });
    }

    public static function scopeMarkAsSeen($query, $userId, $receiverId)
    {
        return $query->where('receiver_id', $userId)->where('sender_id', $receiverId)->where('status','sent')->update(['status' => 'seen']);
    }
}
