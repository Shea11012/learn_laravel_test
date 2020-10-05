<?php

namespace App\Http\Controllers;

use App\Models\Answer;

class AnswersUpVotesController extends Controller
{
    public function store(Answer $answer)
    {
        $answer->voteUp(\Auth::user());
        return $this->success();
    }

    public function destroy(Answer $answer)
    {
        $answer->cancelVoteUp(\Auth::user());
        return $this->success();
    }
}
