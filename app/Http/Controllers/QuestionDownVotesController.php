<?php

namespace App\Http\Controllers;

use App\Models\Question;

class QuestionDownVotesController extends Controller
{
    public function store(Question $question)
    {
        $question->voteDown(\Auth::user());
        return $this->success();
    }

    public function destroy(Question $question)
    {
        $question->cancelVoteDown(\Auth::user());
        return $this->success();
    }
}
