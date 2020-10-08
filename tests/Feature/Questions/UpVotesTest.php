<?php

namespace Tests\Feature\Questions;

use App\Models\Question;
use Tests\Feature\VoteUpContractTest;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpVotesTest extends TestCase
{
    use RefreshDatabase;
    use VoteUpContractTest;

    protected function getVoteUpUri($question = null)
    {
        return $question ? route("question-up-votes.store",['question' => $question]) : route('question-up-votes.store',['question' => 1]);
    }

    protected function upVotes($question)
    {
        return $question->refresh()->votes('vote_up')->get();
    }

    protected function getModel()
    {
        return Question::class;
    }

    protected function getVoteUpStoreUri($model = null)
    {
        return $model ? route("question-up-votes.store",['question' => $model]) : route('question-up-votes.store',['question' => 1]);

    }

    protected function getVoteUpDestroyUri($model = null)
    {
        return $model ? route("question-up-votes.destroy",['question' => $model]) : route('question-up-votes.destroy',['question' => 1]);

    }
}
