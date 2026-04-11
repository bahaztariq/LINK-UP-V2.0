<?php

namespace App\Services;

use App\Models\Reaction;
use App\Models\User;
use App\Notifications\ReactionNotification;
use App\Events\ReactionEvent;

class ReactionService
{
    public function toggleReaction(User $user, array $data)
    {
        $reaction = Reaction::where('user_id', $user->id)
            ->where('reactable_id', $data['reactable_id'])
            ->where('reactable_type', $data['reactable_type'])
            ->first();

        if ($reaction) {
            $reaction->delete();
            return null;
        }

        $newReaction = $user->reactions()->create($data);

        // Notify owner if it's not the current user
        if (isset($data['user_id']) && $user->id != $data['user_id']) {
            $owner = User::find($data['user_id']);
            if ($owner) {
                $owner->notify(new ReactionNotification($user));
                event(new ReactionEvent($user, $owner->id));
            }
        }

        return $newReaction;
    }
}
