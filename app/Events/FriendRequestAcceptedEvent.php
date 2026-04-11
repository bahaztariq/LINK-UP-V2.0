<?php

namespace App\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FriendRequestAcceptedEvent implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public $sender;
    public $receiverId;

    public function __construct($sender, $receiverId)
    {
        $this->sender = $sender;
        $this->receiverId = $receiverId;
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel("New-notification.{$this->receiverId}"),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'sender_name' => $this->sender->name,
            'message' => 'Accepted Your Request',
            'type' => 'accept',
        ];
    }

    public function broadcastAs()
    {
        return 'new.notification';
    }
}
