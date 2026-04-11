<?php

namespace App\View\Composers;

use App\Models\User;
use App\Models\Post;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SidebarComposer
{
    /**
     * Bind data to the view.
     */
    public function compose(View $view): void
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            // 1. Suggested Users (Users the current user is not friends with)
            $friendIds = $user->friend_ids;
            $friendIds->push($user->id);

            $suggestedUsers = User::whereNotIn('id', $friendIds)
                ->inRandomOrder()
                ->limit(3)
                ->get();

            // 2. Trending Topics (Simple hashtag extraction from recent posts)
            $trending = $this->getTrendingTopics();

            $view->with([
                'suggestedUsers' => $suggestedUsers,
                'trendingTopics' => $trending,
            ]);
        }
    }

    /**
     * Get trending topics based on hashtags in posts.
     */
    protected function getTrendingTopics()
    {
        $posts = Post::latest()->limit(100)->pluck('content');
        $hashtags = [];

        foreach ($posts as $content) {
            preg_match_all('/#(\w+)/', $content, $matches);
            if (!empty($matches[1])) {
                foreach ($matches[1] as $tag) {
                    $hashtags[] = '#' . $tag;
                }
            }
        }

        $counts = array_count_values($hashtags);
        arsort($counts);

        $trending = [];
        $limit = 3;
        foreach (array_slice($counts, 0, $limit, true) as $tag => $count) {
            $trending[] = [
                'tag' => $tag,
                'count' => $count,
                'category' => 'Trending'
            ];
        }

        // Fallback if no hashtags are found
        if (empty($trending)) {
             $trending = [
                 ['tag' => '#UnifiedFeed', 'count' => '12.4k', 'category' => 'Technology'],
                 ['tag' => '#UIUX2024', 'count' => '8.5k', 'category' => 'Design'],
                 ['tag' => '#LaravelReverb', 'count' => '5.2k', 'category' => 'Web Dev'],
             ];
        }

        return $trending;
    }
}
