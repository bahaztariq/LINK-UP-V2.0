<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class ExploreController extends Controller
{
    public function index(Request $request)
    {
        $category = $request->query('category', 'For you');

        // Fetch posts with media
        $query = Post::with(['user', 'comments', 'reactions'])
            ->where(function ($q) {
                $q->whereNotNull('image_path')
                  ->orWhereNotNull('video_path');
            });

        // Basic filtering logic if category is not 'For you'
        if ($category !== 'For you' && $category !== 'Trending') {
            $query->where('content', 'like', '%' . $category . '%');
        }

        $posts = $query->latest()->paginate(21);

        return view('explore', compact('posts', 'category'));
    }
}
