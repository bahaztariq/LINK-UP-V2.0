<?php



namespace App\Http\Controllers;
use App\Models\Friendship;
use App\events\PostNotification;
use App\Models\Post;
use App\Models\User;
use App\Notifications\NewMessage;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class PostController extends Controller
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
            'image' => 'nullable|image|max:10240', // 10MB Max
            'video' => 'nullable|mimetypes:video/avi,video/mpeg,video/quicktime,video/mp4|max:51200', // 50MB Max
        ]);

        $postData = ['content' => $validated['content']];

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('posts/images', 'public');
            $postData['image_path'] = $path;
        }

        if ($request->hasFile('video')) {
            $path = $request->file('video')->store('posts/videos', 'public');
            $postData['video_path'] = $path;
        }

        $request->user()->posts()->create($postData);

    $friendships = Friendship::where('status', 'accepted')
    ->where(function ($query) {
        $query->where('requester_id', auth()->id())
              ->orWhere('addressee_id', auth()->id());
    })
    ->get();

    $friends = $friendships->map(function ($fr){
        return $fr->requester_id === auth()->id() ? $fr->addressee_id :
        $fr->requester_id;
    });

    

        $users = User::whereIn('id' , $friends)->get();
        // dd($users);
        Notification::send($users,new NewMessage($request->content , auth()->user(), 'Posts'));
        foreach($users as $user){

            event(new PostNotification(auth()->user() , $user->id ));
        }
    
        return redirect()->route('dashboard');
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        // $this->authorize('update', $post);

        $validated = $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $post->update($validated);

        return back()->with('success', 'Post updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        // $this->authorize('delete', $post);

        $post->delete();

        return redirect()->route('dashboard')->with('success', 'Post deleted successfully.');
    }
}