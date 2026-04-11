<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\NewMessage;


class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $friendIds = $request->user()->friend_ids->push($request->user()->id);

        $posts = Post::with(['user', 'comments', 'reactions'])
            ->whereIn('user_id', $friendIds)
            ->latest()
            ->cursorPaginate(10);
        
        // Suggested users
        $suggestedUsers = User::where('id', '!=', Auth::id())
            ->inRandomOrder()
            ->limit(5)
            ->get();

        return view('dashboard', compact('posts', 'suggestedUsers'));
    }
}
