<?php

namespace Tests\Feature\Questions;


use App\Models\Question;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\VoteDownContractTest;
use Tests\TestCase;

class DownVotesTest extends TestCase
{
    use RefreshDatabase;
    use VoteDownContractTest;

    protected function getVoteDownStoreUri($model = null)
    {
        return $model ? route('question-down-votes.store',['question' => $model]) : route('question-down-votes.store',['question' => 1]);
    }

    protected function getVoteDownDestroyUri($model = null)
    {
        return $model ? route('question-down-votes.destroy',['question' => $model]) : route('question-down-votes.destroy',['question' => 1]);

    }

    protected function upVotes($model)
    {
        return $model->refresh()->votes('vote_down')->get();
    }

    protected function getModel()
    {
        return Question::class;
    }
}