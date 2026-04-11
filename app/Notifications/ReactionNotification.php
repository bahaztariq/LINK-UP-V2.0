<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ReactionNotification extends Notification
{
    use Queueable;

    private $sender;
    private $type;

    public function __construct($sender, $type = 'like')
    {
        $this->sender = $sender;
        $this->type = $type;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'message' => 'Liked your post',
            'sender' => $this->sender->name,
            'type' => 'reaction',
            'time' => now()->format('H:i')
        ];
    }
}
