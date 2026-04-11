<?php

namespace App\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CallEvent implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public $sender;
    public $receiverId;
    public $peerId;
    public $type; // 'incoming-call', 'decline-call', 'cancel-call'

    public function __construct($sender, $receiverId, $peerId, $type = 'incoming-call')
    {
        $this->sender = $sender;
        $this->receiverId = $receiverId;
        $this->peerId = $peerId;
        $this->type = $type;
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
            'sender_id' => $this->sender->id,
            'sender_name' => $this->sender->name,
            'peer_id' => $this->peerId,
            'type' => $this->type,
            'message' => $this->type === 'incoming-call' ? 'is calling you...' : 'Call event',
        ];
    }

    public function broadcastAs()
    {
        return 'new.notification';
    }
}
