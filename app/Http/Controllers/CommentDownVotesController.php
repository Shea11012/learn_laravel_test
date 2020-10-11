<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class CommentDownVotesController extends Controller
{
    public function store(Comment $comment)
    {
        $comment->voteDown(\Auth::user());

        return $this->success();
    }

    public function destroy(Comment $comment)
    {
        $comment->cancelVoteDown(\Auth::user());

        return $this->success();
    }
}
