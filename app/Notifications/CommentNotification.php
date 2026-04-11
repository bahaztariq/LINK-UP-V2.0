<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class CommentNotification extends Notification
{
    use Queueable;

    private $comment;
    private $sender;

    public function __construct($comment, $sender)
    {
        $this->comment = $comment;
        $this->sender = $sender;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'message' => $this->comment->body,
            'sender' => $this->sender->name,
            'type' => 'comment',
            'time' => now()->format('H:i')
        ];
    }
}
