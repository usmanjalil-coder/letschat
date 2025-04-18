<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class SeenAllMessageEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $userId;
    public $receiverId;
    public $receiverImage;
    /**
     * Create a new event instance.
     */
    public function __construct($receiverId, $userId, $receiverImage)
    {
        $this->userId = $userId;
        $this->receiverId = (int)$receiverId;
        $this->receiverImage = $receiverImage;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('chat-channel.'. $this->receiverId),
        ];
    }

    public function broadcastAs() 
    {
        return 'seen-all-message';
    }
}
