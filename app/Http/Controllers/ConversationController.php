<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;

class ConversationController extends Controller
{

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, User $user)
    {
        // Check if a conversation already exists between the two users
        $conversation = Conversation::where(function($query) use ($user) {
            $query->where('user1_id', auth()->id())
                  ->where('user2_id', $user->id);
        })->orWhere(function($query) use ($user) {
            $query->where('user1_id', $user->id)
                  ->where('user2_id', auth()->id());
        })->first();

        if (!$conversation) {
            // Create new conversation
            $conversation = Conversation::create([
                'user1_id' => auth()->id(),
                'user2_id' => $user->id,
            ]);
        }

        
        return redirect()->route('conversations.show', $conversation->id);
    }

    /**
     * Display the specified resource.
     */
    public function index()
    {
        $conversations = Conversation::where('user1_id', auth()->id())
            ->orWhere('user2_id', auth()->id())
            ->with(['user1', 'user2', 'messages' => function($query) {
                $query->latest()->limit(1); // Get latest message for preview
            }])
            ->get();

        return view('messages', compact('conversations'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $conversation = Conversation::with(['messages.user', 'user1', 'user2'])->findOrFail($id);
        
        // Authorization check
        if ($conversation->user1_id !== auth()->id() && $conversation->user2_id !== auth()->id()) {
            abort(403);
        }

        $conversations = Conversation::where('user1_id', auth()->id())
            ->orWhere('user2_id', auth()->id())
            ->with(['user1', 'user2'])
            ->get();

        return view('messages', compact('conversation', 'conversations'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $conversation = Conversation::findOrFail($id);

        // Authorization check
        if ($conversation->user1_id !== auth()->id() && $conversation->user2_id !== auth()->id()) {
            abort(403);
        }

        $conversation->delete();

        return redirect()->route('conversations.index')->with('success', 'Conversation deleted successfully.');
    }
}
