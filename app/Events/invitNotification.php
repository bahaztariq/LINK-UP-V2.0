<?php

namespace App\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class InvitNotification implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public $sender;
    public $receiverId;

    public function __construct($sender, $receiverId)
    {
        $this->sender = $sender;
        $this->receiverId = $receiverId;
    }

 // channel where we ganna send event 
 // ✅ is Working 100%
 public function broadcastOn(): array
 {
     return [
         new PrivateChannel("New-notification.{$this->receiverId}"),
         ];
         }
         
         
         // what we'll backing to JS;
         // ✅ is Working 100%
         public function broadcastWith(): array
         {
             return [
                 'sender_name' => $this->sender->name,
                 'message' => "sent you a friend request",
                 'type' => 'invitation',
                 ];
                 }
                 
    // ✅ is Working 100%
       public function broadcastAs()
    {
        return 'new.notification';
    }
}
