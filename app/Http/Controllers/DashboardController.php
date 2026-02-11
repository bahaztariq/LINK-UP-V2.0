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
        $posts = Post::with(['user', 'comments', 'reactions'])->latest()->get();
        
        // Suggested users
        $suggestedUsers = User::where('id', '!=', Auth::id())
            ->inRandomOrder()
            ->limit(5)
            ->get();

        return view('dashboard', compact('posts', 'suggestedUsers'));
    }


    public function notify(Request $request){

        if(auth()->check()){
            $receiver = User::find(2);
            $message = "hello How're you doing" ;
            if($receiver){
                $receiver->notify(new NewMessage($message , auth()->user() , 'message'));
            }
        }
    }


}
