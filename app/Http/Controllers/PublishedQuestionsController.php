<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PublishedQuestionsController extends Controller
{
    public function store(Question $question)
    {
        $this->authorize('update',$question);
        $question->publish();

        return $this->success();
    }
}
