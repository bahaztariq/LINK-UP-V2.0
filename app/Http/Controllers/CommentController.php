<?php


namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Http\Request;
use App\Notifications\NewMessage;


class CommentController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'body' => 'required|string|max:1000',
            'commentable_id' => 'required|integer',
            'commentable_type' => 'required|string',
            'parent_id' => 'nullable|exists:comments,id',
        ]);

        $comment = $request->user()->comments()->create($validated);
  
    if(auth()->id() != $request->user_id){

    $mssgReaction = User::find($request->user_id);

    if($mssgReaction){
        $message = $request->body;

        $mssgReaction->notify(
            new NewMessage($message, auth()->user(), 'comment')
        );
    }
}


        return back();
    }
}