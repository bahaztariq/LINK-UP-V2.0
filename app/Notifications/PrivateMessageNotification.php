<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PrivateMessageNotification extends Notification
{
    use Queueable;

    private $messageText;
    private $sender;

    public function __construct($messageText, $sender)
    {
        $this->messageText = $messageText;
        $this->sender = $sender;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'message' => $this->messageText,
            'sender' => $this->sender->name,
            'type' => 'message',
            'time' => now()->format('H:i')
        ];
    }
}
