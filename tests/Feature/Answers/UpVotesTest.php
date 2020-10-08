<?php

namespace Tests\Feature\Answers;

use App\Models\Answer;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Tests\Feature\VoteUpContractTest;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpVotesTest extends TestCase
{
    use RefreshDatabase;
    use VoteUpContractTest;

    protected function upVotes($model)
    {
        return $model->refresh()->votes('vote_up')->get();
    }

    protected function getModel()
    {
        return Answer::class;
    }

    protected function getVoteUpStoreUri($model = null)
    {
        return $model ? route('answer-up-votes.store',['answer'=> $model]) : route('answer-up-votes.store',['answer' => 1]);

    }

    protected function getVoteUpDestroyUri($model = null)
    {
        return $model ? route('answer-up-votes.destroy',['answer'=> $model]) : route('answer-up-votes.destroy',['answer' => 1]);

    }
}
