<?php

namespace Tests\Unit;

use App\Models\Answer;
use App\Models\Comment;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AnswerTest extends TestCase
{
    use RefreshDatabase,ActivitiesContractTest;

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

    /** @test */
    public function can_know_it_is_voted_up()
    {
        $user = create(User::class);
        $answer = create(Answer::class);
        create(Vote::class,[
            'user_id' => $user->id,
            'voted_id' => $answer->id,
            'voted_type' => get_class($answer),
        ]);

        self::assertTrue($answer->fresh()->isVotedUp($user));
    }

    /** @test */
    public function can_vote_down_an_answer()
    {
        $this->signIn();
        $answer = create(Answer::class);
        $this->assertDatabaseMissing('votes',[
            'user_id' => auth()->id(),
            'voted_id' => $answer->id,
            'voted_type' => get_class($answer),
            'type' => 'vote_down',
        ]);

        $answer->voteDown(\Auth::user());

        $this->assertDatabaseHas('votes',[
            'user_id' => auth()->id(),
            'voted_id' => $answer->id,
            'voted_type' => get_class($answer),
            'type' => 'vote_down',
        ]);
    }

    /** @test */
    public function can_cancel_vote_down_answer()
    {
        $this->signIn();
        $answer = create(Answer::class);
        $answer->voteDown(\Auth::user());
        $answer->cancelVoteDown(\Auth::user());
        $this->assertDatabaseMissing('votes',[
            'user_id' => auth()->id(),
            'voted_id' => $answer->id,
            'voted_type' => get_class($answer),
        ]);
    }

    /** @test */
    public function can_vote_down_only_once()
    {
        $this->signIn();
        $answer = create(Answer::class);
        try {
            $this->post(route('answer-down-votes.destroy',['answer' => $answer]));
            $this->post(route('answer-down-votes.destroy',['answer' => $answer]));
        } catch (\Exception $exception) {
            self::fail('Can not vote down twice');
        }

        self::assertCount(1,$answer->refresh()->votes('vote_down')->get());
    }

    /** @test */
    public function can_know_it_is_voted_down()
    {
        $user = create(User::class);
        $answer = create(Answer::class);
        create(Vote::class,[
            'user_id' => $user->id,
            'voted_id' => $answer->id,
            'voted_type' => get_class($answer),
            'type' => 'vote_down',
        ]);

        self::assertTrue($answer->refresh()->isVotedDown($user));
    }

    /** @test */
    public function an_answer_has_many_comments()
    {
        $answer = create(Answer::class);
        create(Comment::class,[
            'commented_id' => $answer->id,
            'commented_type' => $answer->getMorphClass(),
            'content' => 'it is a comment',
        ]);

        self::assertInstanceOf(MorphMany::class,$answer->comments());
    }

    /** @test */
    public function can_comment_an_answer()
    {
        $answer = create(Answer::class);
        $answer->comment('it is content',create(User::class));
        self::assertEquals(1,$answer->refresh()->comments()->count());
    }

    /** @test */
    public function can_get_comments_count_attribute()
    {
        $answer = create(Answer::class);
        $answer->comment($this->faker->sentence(10,true),create(User::class));
        self::assertEquals(1,$answer->refresh()->commentsCount);
    }

    /** @test */
    public function can_get_comment_endpoint_attribute()
    {
        $answer = create(Answer::class);
        $answer->comment('it is content',create(User::class));
        self::assertEquals(route("answer-comments.index",[$answer]),$answer->refresh()->commentEndpoint);
    }

    protected function getActivityModel()
    {
        return create(Answer::class);
    }

    protected function getActivityType()
    {
        return 'created_answer';
    }
}
