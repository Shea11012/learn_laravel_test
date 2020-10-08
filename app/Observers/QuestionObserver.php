<?php


namespace App\Observers;


use App\Jobs\TranslateSlug;
use App\Models\Question;
use App\Translator\Translator;

class QuestionObserver
{
    public function created(Question $question)
    {
        $translator = app(Translator::class);
        if (!$question->slug) {
            dispatch(new TranslateSlug($question));
        }
    }
}