<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('chat-channel.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('typing-status.{receiverId}', function ($user, $receiverId) {
    Log::info("Channel authorization for user: {$user->id} on receiverId: {$receiverId}");
    return Auth::check();
});

// For online status
Broadcast::channel('online-users', function ($user) {
    return $user;
});