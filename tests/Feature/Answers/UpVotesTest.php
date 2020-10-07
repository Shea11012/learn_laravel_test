<?php

namespace Tests\Feature\Answers;

use App\Models\Answer;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpVotesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_can_not_vote_up()
    {
        $this->expectException(AuthenticationException::class);
        $answer = create(Answer::class);
        $this->post(route('answer-up-votes.store',['answer' => $answer]));
    }

    /** @test */
    public function authenticate_user_can_vote_up()
    {
        $this->signIn();
        $answer = create(Answer::class);
        $response = $this->post(route('answer-up-votes.store',['answer' => $answer]));
        $this->assertResponseSuccess($response,['code' => 200]);

        self::assertCount(1,$answer->refresh()->votes('vote_up')->get());
    }

    /** @test */
    public function an_authenticated_user_can_cancel_vote_up()
    {
        $this->signIn();
        $answer = create(Answer::class);
        $this->post(route('answer-up-votes.store',['answer' => $answer->id]));

        self::assertCount(1,$answer->refresh()->votes('vote_up')->get());

        $this->delete(route('answer-up-votes.destroy',['answer' => $answer->id]));

        self::assertCount(0,$answer->refresh()->votes('vote_up')->get());
    }

    /** @test */
    public function can_vote_up_only_once()
    {
        $this->signIn();

        $answer = create(Answer::class);

        try {
            $this->post(route('answer-up-votes.destroy',['answer' => $answer]));
            $this->post(route('answer-up-votes.destroy',['answer' => $answer]));
        } catch (\Exception $exception) {
            self::fail('Can not vote up twice.');
        }

        self::assertCount(1,$answer->refresh()->votes('vote_up')->get());
    }

    /** @test */
    public function can_know_it_is_voted_up()
    {
        $this->signIn();

        $answer = create(Answer::class);
        $this->post(route('answer-up-votes.store',['answer' => $answer]));
        self::assertTrue($answer->fresh()->isVotedUp(\Auth::user()));
    }

    /** @test */
    public function can_know_up_votes_count()
    {
        $this->signIn();
        $answer = create(Answer::class);
        $this->post(route('answer-up-votes.store',['answer' => $answer]));
        self::assertEquals(1,$answer->fresh()->upVotesCount);

        // 另外一个用户
        $this->signIn();
        $this->post(route('answer-up-votes.store',['answer' => $answer]));
        self::assertEquals(2,$answer->refresh()->upVotesCount);

    }
}
