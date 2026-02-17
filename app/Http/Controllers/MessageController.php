<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Events\MessageSent;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:1000',
            'receiver_id' => 'required|exists:users,id',
            'conversation_id' => 'required|exists:conversations,id',
            'expires_in' => 'nullable|integer|min:1', // in minutes
        ]);

        $expiresAt = null;
        if (isset($validated['expires_in']) && $validated['expires_in'] > 0) {
            $expiresAt = now()->addMinutes($validated['expires_in']);
        }

        $message = $request->user()->messages()->create([
            'content' => $validated['content'],
            'receiver_id' => $validated['receiver_id'],
            'conversation_id' => $validated['conversation_id'],
            'expires_at' => $expiresAt,
        ]);

        event(new MessageSent($message, $validated['conversation_id']));

        return response()->json(['message' => 'Message sent successfully']);
    }

    public function update(Request $request, Message $message)
    {
        if ($request->user()->id !== $message->sender_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $message->update([
            'content' => $validated['content'],
            'is_edited' => true,
        ]);

        event(new \App\Events\MessageUpdated($message));

        return response()->json(['message' => 'Message updated successfully', 'data' => $message]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
