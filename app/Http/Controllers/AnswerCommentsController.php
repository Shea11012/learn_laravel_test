<?php

namespace App\Http\Controllers;

use App\Http\Resources\AnswerCommentResource;
use App\Models\Answer;
use Illuminate\Http\Request;

class AnswerCommentsController extends Controller
{
    public function index(Answer $answer)
    {
        $comments = $answer->comments()->paginate(10);

        array_map(function (&$item){
            return $this->appendVotedAttribute($item);
        },$comments->items());

        return AnswerCommentResource::make($comments);
    }

    public function store(Answer $answer)
    {
        $this->validate(\request(),[
            'content' => 'required',
        ]);

        $answer->comment(\request('content'),\Auth::user());

        return $this->success();
    }
}
