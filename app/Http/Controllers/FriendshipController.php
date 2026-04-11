<?php



namespace App\Http\Controllers;
use App\Http\Requests\StoreFriendshipRequest;
use App\Services\FriendshipService;
use App\Models\Friendship;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FriendshipController extends Controller
{
    protected $friendshipService;

    public function __construct(FriendshipService $friendshipService)
    {
        $this->friendshipService = $friendshipService;
    }
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
    public function store(StoreFriendshipRequest $request)
    {
        $result = $this->friendshipService->sendRequest($request->user(), $request->validated()['addressee_id']);

        if (isset($result['error'])) {
            return back()->with('error', $result['error']);
        }

        return back();
    }

    /**
     * Accept a friend request.
     */
    public function update(Request $request, $id)
    {
        $this->friendshipService->acceptRequest($request->user(), $id);

        return back();
    }

    /**
     * Decline or Cancel a friend request / Remove friend.
     */
    public function destroy(Request $request, $id)
    {
        $this->friendshipService->removeFriendship($request->user(), $id);

        return back();
    }

    /**
     * Block a user.
     */
    public function block(Request $request, $userId)
    {
        $this->friendshipService->blockUser($request->user(), $userId);

        return back()->with('success', 'User blocked successfully.');
    }
}