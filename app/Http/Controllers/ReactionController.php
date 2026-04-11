<?php



namespace App\Http\Controllers;
use App\Http\Requests\ToggleReactionRequest;
use App\Services\ReactionService;
use App\Models\User;
use App\Models\Reaction;
use Illuminate\Http\Request;

class ReactionController extends Controller
{
    protected $reactionService;

    public function __construct(ReactionService $reactionService)
    {
        $this->reactionService = $reactionService;
    }
    /**
     * Toggle a reaction (like/unlike).
     */
    public function toggle(ToggleReactionRequest $request)
    {
        $this->reactionService->toggleReaction($request->user(), $request->validated());

        return back();
    }
}