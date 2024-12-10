<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ChatEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $userId;
    public $message;
    public $userName;
    public $renderImage;
    public $createdAt;
    public $message_type;
    public $media;
    public $audioUrl;
    /**
     * Create a new event instance.
     */
    public function __construct($userId , $message, $userName, $renderImage, $createdAt, $message_type , $media, $audioUrl)
    {
        $this->userId = $userId;
        $this->message = $message;
        $this->userName = $userName;
        $this->renderImage = $renderImage;
        $this->createdAt = $createdAt;
        $this->message_type = $message_type;
        $this->media = $media;
        $this->audioUrl = $audioUrl;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('chat-channel.'. $this->userId),
        ];
    }

    public function broadcastAs()
    {
        return 'chat-event';
    }
}
