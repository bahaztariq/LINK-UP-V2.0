<?php



namespace App\Http\Controllers;
use App\Models\User;
use App\Notifications\NewMessage;
use App\Models\Reaction;
use Illuminate\Http\Request;
use App\Events\LikeNotification;

class ReactionController extends Controller
{
    /**
     * Toggle a reaction (like/unlike).
     */
    public function toggle(Request $request)
    {
        $validated = $request->validate([
            'reactable_id' => 'required|integer',
            'reactable_type' => 'required|string',
            'type' => 'nullable|string|in:like', // default to like
        ]);

        $reaction = Reaction::where('user_id', $request->user()->id)
            ->where('reactable_id', $validated['reactable_id'])
            ->where('reactable_type', $validated['reactable_type'])
            ->first();




        if ($reaction) {
            $reaction->delete();
            return back();
        }

        $request->user()->reactions()->create($validated);

        if(auth()->id() != $request->user_id ){

            $mssgReaction = User::find($request->user_id);
            if($mssgReaction){
                $mssgReaction->notify(new NewMessage( 'Like Your Post' , auth()->user() , 'like'));
                event(new LikeNotification(auth()->user() , $mssgReaction['id']));
                }
                }
                

        return back();
    }
}