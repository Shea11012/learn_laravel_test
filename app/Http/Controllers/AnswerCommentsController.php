<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use Illuminate\Http\Request;

class AnswerCommentsController extends Controller
{
    public function store(Answer $answer)
    {
        $this->validate(\request(),[
            'content' => 'required',
        ]);

        $answer->comment(\request('content'),\Auth::user());

        return $this->success();
    }
}
