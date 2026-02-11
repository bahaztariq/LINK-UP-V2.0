<?php



namespace App\Http\Controllers;

use App\Models\Reaction;
use Illuminate\Http\Request;

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

        // $mssgReaction = User::fint()


        return back();
    }
}