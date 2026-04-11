<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Services\CommentService;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Http\Request;
use App\Notifications\NewMessage;

class CommentController extends Controller
{
    protected $commentService;

    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCommentRequest $request)
    {
        $this->commentService->createComment($request->user(), $request->validated());

        return back();
    }
}