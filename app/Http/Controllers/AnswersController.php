<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Question;

class AnswersController extends Controller
{
    public function store($questionId)
    {
        $question = Question::published()->findOrFail($questionId);

        $this->validate(\request(),[
            'content' => 'required'
        ]);

        $question->addAnswer([
            'user_id' => auth()->id(),
            'content' => \request('content'),
        ]);

        return response()->json([],201);
    }

    public function destroy(Answer $answer)
    {
        $this->authorize('delete',$answer);

        $answer->delete();

        return $this->success();
    }
}
