<?php

namespace App\Services;

use App\Models\Friendship;
use App\Models\User;
use App\Notifications\FriendRequestNotification;
use App\Notifications\FriendRequestAcceptedNotification;
use App\Events\FriendRequestEvent;
use App\Events\FriendRequestAcceptedEvent;

class FriendshipService
{
    public function sendRequest(User $user, int $addresseeId)
    {
        if ($user->id === $addresseeId) {
            return ['error' => 'You cannot send a friend request to yourself.'];
        }

        $addressee = User::find($addresseeId);
        if (!$addressee) {
            return ['error' => 'User not found.'];
        }

        // Check if already friends or pending
        if ($user->isFriendWith($addressee) || 
            $user->getPendingFriendRequestTo($addressee) || 
            $user->getPendingFriendRequestFrom($addressee)) {
            return ['error' => 'Friendship already exists or request is pending.'];
        }

        $friendship = Friendship::create([
            'requester_id' => $user->id,
            'addressee_id' => $addresseeId,
            'status' => 'pending',
        ]);

        $addressee->notify(new FriendRequestNotification($user));
        event(new FriendRequestEvent($user, $addresseeId));

        return $friendship;
    }

    public function acceptRequest(User $user, int $friendshipId)
    {
        $friendship = Friendship::where('id', $friendshipId)
            ->where('addressee_id', $user->id)
            ->firstOrFail();

        $friendship->update(['status' => 'accepted']);

        $requester = User::find($friendship->requester_id);
        if ($requester) {
            $requester->notify(new FriendRequestAcceptedNotification($user));
            event(new FriendRequestAcceptedEvent($user, $requester->id));
        }

        return $friendship;
    }

    public function removeFriendship(User $user, int $friendshipId)
    {
        $friendship = Friendship::where('id', $friendshipId)
            ->where(function ($query) use ($user) {
                $query->where('requester_id', $user->id)
                      ->orWhere('addressee_id', $user->id);
            })
            ->firstOrFail();

        $friendship->delete();

        return true;
    }

    public function blockUser(User $user, int $blockedUserId)
    {
        // Check if there is already a friendship record
        $friendship = Friendship::where(function($query) use ($user, $blockedUserId) {
            $query->where('requester_id', $user->id)
                  ->where('addressee_id', $blockedUserId);
        })->orWhere(function($query) use ($user, $blockedUserId) {
            $query->where('requester_id', $blockedUserId)
                  ->where('addressee_id', $user->id);
        })->first();

        if ($friendship) {
            $friendship->update([
                'requester_id' => $user->id,
                'addressee_id' => $blockedUserId,
                'status' => 'blocked'
            ]);
        } else {
            $friendship = Friendship::create([
                'requester_id' => $user->id,
                'addressee_id' => $blockedUserId,
                'status' => 'blocked'
            ]);
        }

        return $friendship;
    }
}
