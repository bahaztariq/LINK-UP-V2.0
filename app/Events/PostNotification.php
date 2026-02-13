<?php

namespace App\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PostNotification implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public $sender;
    public $receiverId;



    /**
     * Create a new event instance.
     */
    public function __construct($sender , $receiverId)
    {
        $this->sender = $sender;
        $this->receiverId = $receiverId;
        }

        /**
         * Get the channels the event should broadcast on.
        *
        * @return array<int, \Illuminate\Broadcasting\Channel>
        */
        public function broadcastOn(): array
        {
            return [
                new PrivateChannel("New-notification.{$this->receiverId}"),
                ];
                }
                
                public function broadcastWith(): array{

                    return[
                        
        'sender_name' => $this->sender->name,
        'message' => 'Add New Post' ,
        'type' => 'post',

        ];
    }


    public function  broadcastAs(){
        return 'new.notification';
    }













}
