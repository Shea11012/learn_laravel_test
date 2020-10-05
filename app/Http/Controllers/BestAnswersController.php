<?php

namespace App\Http\Controllers;

use App\Models\Answer;

class BestAnswersController extends Controller
{
    public function store(Answer $answer)
    {
        $this->authorize('update',$answer->question);
        $answer->question->markAsBestAnswer($answer);

        return response()->json(['code' => 200]);
    }
}
