<?php

namespace App\Http\Controllers;

use App\Models\Question;

class QuestionUpVotesController extends Controller
{
    public function store(Question $question)
    {
        $question->voteUp(\Auth::user());

        return $this->success();
    }

    public function destroy(Question $question)
    {
        $question->cancelVoteUp(\Auth::user());
        return $this->success();
    }
}
