<?php

namespace App\Services;

use App\Models\Post;
use App\Models\User;
use App\Notifications\PostCreatedNotification;
use App\Events\PostEvent;
use Illuminate\Support\Facades\Notification;

class PostService
{
    /**
     * Create a new post and notify friends.
     *
     * @param User $user
     * @param array $data
     * @param array $files
     * @return Post
     */
    public function createPost(User $user, array $data, array $files = []): Post
    {
        $postData = ['content' => $data['content']];

        if (isset($files['image'])) {
            $postData['image_path'] = $files['image']->store('posts/images', 'public');
        }

        if (isset($files['video'])) {
            $postData['video_path'] = $files['video']->store('posts/videos', 'public');
        }

        $post = $user->posts()->create($postData);

        $this->notifyFriends($user, $data['content']);

        return $post;
    }

    /**
     * Notify all friends about the new post.
     *
     * @param User $user
     * @param string $content
     * @return void
     */
    protected function notifyFriends(User $user, string $content): void
    {
        $friends = $user->friends;

        if ($friends->isNotEmpty()) {
            Notification::send($friends, new PostCreatedNotification($content, $user));
            
            foreach ($friends as $friend) {
                event(new PostEvent($user, $friend->id));
            }
        }
    }
}
