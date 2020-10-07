<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;

class QuestionsController extends Controller
{
    public function index()
    {
        
    }

    public function show($questionId)
    {
        $question = Question::published()->findOrFail($questionId);

        return response()->json([
            'question'=>$question,
            'answers' => $question->answers()->paginate(20),
        ]);
    }

    public function store()
    {
        $this->validate(\request(),[
            'title' => 'required',
            'content' => 'required',
            'category_id' => 'required|exists:categories,id'
        ]);

        $question = Question::create([
            'user_id' => auth()->id(),
            'category_id' => \request('category_id'),
            'title' => \request('title'),
            'content' => \request('content'),
        ]);

        return $this->success();
    }
}
