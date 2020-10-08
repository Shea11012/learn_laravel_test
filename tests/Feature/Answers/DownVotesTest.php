<?php

namespace Tests\Feature\Answers;

use App\Models\Answer;
use Illuminate\Auth\AuthenticationException;
use Tests\Feature\VoteDownContractTest;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DownVotesTest extends TestCase
{
    use RefreshDatabase;
    use VoteDownContractTest;


    protected function getVoteDownStoreUri($model = null)
    {
        return $model ? route('answer-down-votes.store',['answer'=> $model]) : route('answer-down-votes.store',['answer' => 1]);

    }

    protected function getVoteDownDestroyUri($model = null)
    {
        return $model ? route('answer-down-votes.destroy',['answer'=> $model]) : route('answer-down-votes.destroy',['answer' => 1]);

    }

    protected function upVotes($model)
    {
        return $model->refresh()->votes('vote_down')->get();
    }

    protected function getModel()
    {
        return Answer::class;
    }
}
