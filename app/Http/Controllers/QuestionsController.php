<?php

namespace App\Http\Controllers;

use App\Http\Resources\QuestionsListResource;
use App\Http\Resources\QuestionsShowResource;
use App\Models\Category;
use App\Models\Question;
use App\Models\User;
use App\Filters\QuestionFilter;

class QuestionsController extends Controller
{
    public function index(Category $category,QuestionFilter $filters)
    {
        if ($category->exists) {
            $questions = Question::published()->where('category_id',$category->id);
        } else {
            $questions = Question::published();
        }

        $questions = $questions->filter($filters)->paginate(20);
//        $rs = csPaginate($questions->toBase(),$questions->total(),$questions->perPage());
        return new QuestionsListResource($questions);
    }

    public function show($questionId)
    {
        $question = Question::published()->findOrFail($questionId);

        return new QuestionsShowResource($question);
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
