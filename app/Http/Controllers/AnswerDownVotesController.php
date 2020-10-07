<?php

namespace App\Http\Controllers;

use App\Models\Answer;

class AnswerDownVotesController extends Controller
{
    public function store(Answer $answer)
    {
        $answer->voteDown(\Auth::user());
        return $this->success();
    }

    public function destroy(Answer $answer)
    {
        $answer->cancelVoteDown(\Auth::user());
        return $this->success();
    }
}
