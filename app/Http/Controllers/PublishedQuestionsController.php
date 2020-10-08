<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\User;
use App\Notifications\YouWereInvited;

class PublishedQuestionsController extends Controller
{
    public function store(Question $question)
    {
        $this->authorize('update',$question);
        preg_match_all('#@([^\s.]+)#',$question->content,$matches);
        $names = $matches[1];
        foreach ($names as $name) {
            $user = User::whereName($name)->first();
            if ($user) {
                $user->notify(new YouWereInvited($question));
            }
        }

        $question->publish();

        return $this->success();
    }
}
