<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;

class QuestionCommentsController extends Controller
{
    public function store($questionId)
    {
        $this->validate(\request(),[
            'content' => 'required',
        ]);
        $question = Question::published()->findOrFail($questionId);

        $comment = $question->comment(
            \request('content'),\Auth::user()
        );

        return $this->success();
    }
}
