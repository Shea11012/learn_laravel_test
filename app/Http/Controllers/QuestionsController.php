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

        array_map(function (&$item) {
            return $this->appendAttribute($item);
        },$questions->items());
        return new QuestionsListResource($questions);
    }

    public function show($category,$questionId)
    {
        $question = Question::published()->findOrFail($questionId);

        $answers = $question->answers()->paginate(20);

        array_map(function (&$item) {
            return $this->appendVotedAttribute($item);
        },$answers->items());

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

    protected function appendAttribute($item)
    {
        $user = \Auth::user();

        $item->isVotedUp = $item->isVotedUp($user);
        $item->isVotedDown = $item->isVotedDown($user);
        $item->isSubscribedTo = $item->isSubscribedTo($user);

        return $item;
    }
}
