<?php

namespace App\Http\Controllers;

use App\Events\CallEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CallController extends Controller
{
    public function signal(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'peer_id' => 'required|string',
            'type' => 'required|string', // incoming-call, decline-call, cancel-call, accept-call
        ]);

    event(new CallEvent(
            Auth::user(),
            $request->receiver_id,
            $request->peer_id,
            $request->type
        ));

        return response()->json(['status' => 'Signal sent']);
    }
}
