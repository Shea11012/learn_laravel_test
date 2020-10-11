<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class CommentUpVotesController extends Controller
{
    public function store(Comment $comment)
    {
        $comment->voteUp(\Auth::user());

        return $this->success();
    }

    public function destroy(Comment $comment)
    {
        $comment->cancelVoteUp(\Auth::user());

        return $this->success();
    }
}
