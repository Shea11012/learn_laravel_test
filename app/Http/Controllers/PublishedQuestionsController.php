<?php

namespace App\Http\Controllers;

use App\Events\PublishQuestion;
use App\Models\Question;

class PublishedQuestionsController extends Controller
{
    public function store(Question $question)
    {
        $this->authorize('update',$question);

        $question->publish();

        event(new PublishQuestion($question));

        return $this->success();
    }
}
