<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Events\MessageSent;
use App\Events\mssgNotification;

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
        ]);

        $message = $request->user()->messages()->create([
            'content' => $validated['content'],
            'receiver_id' => $validated['receiver_id'],
            'conversation_id' => $validated['conversation_id'],
            ]);
            
            
            
            event(new MessageSent($message,$validated['conversation_id']));
            
            // $receiver = User::find($validated['receiver_id']);
            // if ($receiver) {
                // $notificationText = 'Sent You A Message';

                //  $receiver->notify(
                //      new NewMessage($notificationText, auth()->user(), 'message')
                //  );
                // }
             
                 event(new mssgNotification(
                     auth()->user(),
                     $validated['receiver_id'],
                     $validated['conversation_id']
                 ));

            // dd($receiver);
          
                
      

        return response()->json(['message' => 'Message sent successfully']);
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
