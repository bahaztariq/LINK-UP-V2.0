<?php



namespace App\Http\Controllers;

use App\Models\Friendship;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\NewMessage;
use App\Events\InvitNotification;
use App\Events\acceptInviNotification;


class FriendshipController extends Controller
{
    /**
     * Display a listing of friends and requests.
     */
    public function index(Request $request)
    {
        $acceptedFriends = $request->user()->friends;
        $pendingRequests = $request->user()->pending_requests;

        return view('friends.index', compact('acceptedFriends', 'pendingRequests'));
    }

    /**
     * Send a friend request.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'addressee_id' => 'required|exists:users,id',
        ]);

        if ($request->user()->id === $validated['addressee_id']) {
            return back()->with('error', 'You cannot send a friend request to yourself.');
        }

        // Check availability
        if ($request->user()->isFriendWith(\App\Models\User::find($validated['addressee_id'])) ||
            $request->user()->getPendingFriendRequestTo(\App\Models\User::find($validated['addressee_id'])) ||
            $request->user()->getPendingFriendRequestFrom(\App\Models\User::find($validated['addressee_id']))) {
            return back();
        }

        $address = \App\Models\User::find($validated['addressee_id']);
        // dd($address['id']);
        Friendship::create([
            'requester_id' => $request->user()->id,
            'addressee_id' => $validated['addressee_id'],
            'status' => 'pending',
        ]);

        $message = 'You received a new message';
        $address->notify(new NewMessage($message , auth()->user()  , 'invitation'));
         
        // ✅ is Working 100%
        event(new InvitNotification(auth()->user(), $address['id'] ));

        return back();
    }

    /**
     * Accept a friend request.
     */
    public function update(Request $request, $id)
    {
        $friendship = Friendship::where('id', $id)
            ->where('addressee_id', $request->user()->id)
            ->firstOrFail();

        $friendship->update(['status' => 'accepted']);
// dd($friendship['addressee_id']);
            event(new acceptInviNotification(auth()->user() , $friendship['requester_id'] ));

        return back();
    }

    /**
     * Decline or Cancel a friend request / Remove friend.
     */
    public function destroy(Request $request, $id)
    {
        $friendship = Friendship::where('id', $id)
            ->where(function ($query) use ($request) {
                $query->where('requester_id', $request->user()->id)
                      ->orWhere('addressee_id', $request->user()->id);
            })
            ->firstOrFail();

        $friendship->delete();

        return back();
    }
}