<?php

namespace App\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class mssgNotification implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public $sender;
    public $receiverId;
    public $room;
    /**
     * Create a new event instance.
     */
      public function __construct($sender, $receiverId , $room)
    {
        $this->sender = $sender;
        $this->receiverId = $receiverId;
        $this->room = $room;
    }


   
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel("New-notification.{$this->receiverId}"),
        ];
        // dd($this->room);
        }

    public function broadcastWith(): array{
        return [
       'sender_name' => $this->sender->name,
       'message' => 'Sent You A Message',
       'type' => 'mssg',
       'room' => $this->room,
        ];
    }

        public function broadcastAs()
    {
        return 'new.notification';
    }




}

