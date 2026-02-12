<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
<<<<<<< HEAD
=======

Broadcast::channel('chat-message.{id}', function ($user, $id) {
    $conversation = \App\Models\Conversation::find($id);
    return $conversation && ($conversation->user1_id == $user->id || $conversation->user2_id == $user->id);
});
>>>>>>> 16c168ee5550e0313af1d1e8dec839cb3e2fea77
