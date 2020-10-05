<?php

namespace Tests\Unit;

use App\Models\Answer;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AnswerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_knows_if_it_is_the_best()
    {
        $answer = create(Answer::class);
        self::assertFalse($answer->isBest());

        $answer->question->update(['best_answer_id' => $answer->id]);

        self::assertTrue($answer->isBest());
    }

    /** @test */
    public function an_answer_belongs_to_an_owner()
    {
        $answer = create(Answer::class);
        self::assertInstanceOf(User::class,$answer->owner);
    }

    /** @test */
    public function can_vote_up_an_answer()
    {
        $this->signIn();
        $answer = create(Answer::class);

        $this->assertDatabaseMissing('votes',[
            'user_id' => auth()->id(),
            'voted_id' => $answer->id,
            'voted_type' => get_class($answer),
            'type' => 'vote_up',
        ]);

        $answer->voteUp(\Auth::user());

        $this->assertDatabaseHas('votes',[
            'user_id' => auth()->id(),
            'voted_id' => $answer->id,
            'voted_type' => get_class($answer),
            'type' => 'vote_up',
        ]);
    }

    /** @test */
    public function can_cancel_vote_up_an_answer()
    {
        $this->signIn();

        $answer = create(Answer::class);
        $answer->voteUp(\Auth::user());
        $answer->cancelVoteUp(\Auth::user());
        $this->assertDatabaseMissing('votes',[
            'user_id' => auth()->id(),
            'voted_id' => $answer->id,
            'voted_type' => get_class($answer),
        ]);
    }
}
