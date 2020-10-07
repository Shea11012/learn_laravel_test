<?php

namespace Tests\Feature\Answers;

use App\Models\Answer;
use Illuminate\Auth\AuthenticationException;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DownVotesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_can_not_vote_down()
    {
        $this->expectException(AuthenticationException::class);
        $answer = create(Answer::class);
        $this->post(route('answer-down-votes.store',['answer' => $answer]));
    }

    /** @test */
    public function authenticated_user_can_vote_down()
    {
        $this->signIn();
        $answer = create(Answer::class);
        $response = $this->post(route('answer-down-votes.store',['answer' => $answer]));
        $this->assertResponseSuccess($response,['code' => 200]);

        self::assertCount(1,$answer->refresh()->votes('vote_down')->get());
    }

    /** @test */
    public function an_authenticated_user_can_cancel_vote_down()
    {
        $this->signIn();
        $answer = create(Answer::class);
        $this->post(route('answer-down-votes.store',['answer' => $answer]));
        self::assertCount(1,$answer->refresh()->votes('vote_down')->get());

        $this->delete(route('answer-down-votes.destroy',['answer' => $answer]));
        self::assertCount(0,$answer->refresh()->votes('vote_down')->get());
    }

    /** @test */
    public function can_know_it_is_voted_down()
    {
        $this->signIn();
        $answer = create(Answer::class);
        $this->post(route('answer-down-votes.destroy',['answer' => $answer]));
        self::assertTrue($answer->refresh()->isVotedDown(\Auth::user()));
    }

    /** @test */
    public function can_know_down_votes_count()
    {
        $this->signIn();
        $answer = create(Answer::class);
        $this->post(route('answer-down-votes.store',['answer' => $answer]));
        self::assertEquals(1,$answer->refresh()->downVotesCount);

        $this->signIn();
        $this->post(route('answer-down-votes.store',['answer' => $answer]));
        self::assertEquals(2,$answer->refresh()->downVotesCount);
    }
}
