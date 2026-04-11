<?php

namespace App\Services;

use App\Models\Message;
use App\Models\User;
use App\Notifications\PrivateMessageNotification;
use App\Events\MessageSent;
use App\Events\MessageNotificationEvent;

class MessageService
{
    public function sendMessage(User $user, array $data)
    {
        $expiresAt = null;
        if (isset($data['expires_in']) && $data['expires_in'] > 0) {
            $expiresAt = now()->addMinutes($data['expires_in']);
        }

        $message = $user->messages()->create([
            'content' => $data['content'],
            'receiver_id' => $data['receiver_id'],
            'conversation_id' => $data['conversation_id'],
            'expires_at' => $expiresAt,
        ]);

        event(new MessageSent($message, $data['conversation_id']));

        $receiver = User::find($data['receiver_id']);
        if ($receiver) {
            $receiver->notify(new PrivateMessageNotification($data['content'], $user));
            event(new MessageNotificationEvent($user, $receiver->id, $data['conversation_id']));
        }

        return $message;
    }

    public function updateMessage(User $user, Message $message, string $content)
    {
        if ($user->id !== $message->sender_id) {
            return false;
        }

        $message->update([
            'content' => $content,
            'is_edited' => true,
        ]);

        event(new \App\Events\MessageUpdated($message));

        return $message;
    }
}
