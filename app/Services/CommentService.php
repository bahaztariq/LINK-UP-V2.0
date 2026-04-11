<?php

namespace App\Services;

use App\Models\Comment;
use App\Models\User;
use App\Notifications\CommentNotification;
use App\Events\CommentEvent;
use Illuminate\Support\Facades\Auth;

class CommentService
{
    /**
     * Create a new comment and notify the post owner.
     *
     * @param User $user
     * @param array $data
     * @return Comment
     */
    public function createComment(User $user, array $data): Comment
    {
        $comment = $user->comments()->create($data);

        // Notify the person who owns the post/commentable item
        if (isset($data['user_id']) && $user->id != $data['user_id']) {
            $owner = User::find($data['user_id']);
            if ($owner) {
                $owner->notify(
                    new CommentNotification($comment, $user)
                );
                event(new CommentEvent($user, $owner->id, $data['body']));
            }
        }

        return $comment;
    }
}
