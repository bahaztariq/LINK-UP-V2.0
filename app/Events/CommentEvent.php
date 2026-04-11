<?php

namespace App\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CommentEvent implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public $sender;
    public $receiverId;
    public $message;

    public function __construct($sender, $receiverId, $message)
    {
        $this->sender = $sender;
        $this->receiverId = $receiverId;
        $this->message = $message;
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
            'message' => $this->message,
            'type' => 'comment',
        ];
    }

    public function broadcastAs()
    {
        return 'new.notification';
    }
}
