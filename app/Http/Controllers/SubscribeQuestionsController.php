<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;

class SubscribeQuestionsController extends Controller
{
    public function store(Question $question)
    {
        $question->subscribe(\Auth::user());

        return $this->success();
    }

    public function destroy(Question $question)
    {
        $question->unsubscribe(\Auth::user());
        return $this->success();
    }
}
