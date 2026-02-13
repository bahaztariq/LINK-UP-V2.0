<?php

use Illuminate\Support\Facades\Broadcast;


Broadcast::channel('chat-message.{id}', function ($user, $id) {
    $conversation = \App\Models\Conversation::find($id);
    return $conversation && ($conversation->user1_id == $user->id || $conversation->user2_id == $user->id);
});

Broadcast::channel('New-notification.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
    // user id 1 ana === 1
});
