<?php

namespace Tests\Unit;

use App\Models\Answer;
use App\Models\Question;
use App\Models\Subscription;
use App\Models\User;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuestionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_question_has_many_answers()
    {
        $question = factory(Question::class)->create();
        factory(Answer::class)->create(['question_id' => $question->id]);

        self::assertInstanceOf(HasMany::class, $question->answers());
    }

    /** @test */
    public function questions_with_published_at_date_are_published()
    {
        $publishedQuestion1 = factory(Question::class)->states('published')->create();

        $publishedQuestion2 = factory(Question::class)->states('published')->create();

        $unpublishedQuestion = factory(Question::class)->states('unpublished')->create();

        $publishedQuestions = Question::published()->get();

        self::assertTrue($publishedQuestions->contains($publishedQuestion1));
        self::assertTrue($publishedQuestions->contains($publishedQuestion2));
        self::assertFalse($publishedQuestions->contains($unpublishedQuestion));
    }

    /** @test */
    public function can_mark_an_answer_as_best()
    {
        $question = create(Question::class, ['best_answer_id' => null]);
        $answer = create(Answer::class, ['question_id' => $question->id]);
        $question->markAsBestAnswer($answer);
        self::assertSame($question->best_answer_id, $answer->id);
    }

    /** @test */
    public function a_question_belongs_to_a_creator()
    {
        $question = create(Question::class);
        self::assertInstanceOf(User::class,$question->creator);
    }

    /** @test */
    public function can_publish_a_question()
    {
        $question = create(Question::class,['published_at' => null]);

        self::assertCount(0,Question::published()->get());
        $question->publish();
        self::assertCount(1,Question::published()->get());
    }

    /** @test */
    public function it_can_detect_all_invited_users()
    {
        $question = create(Question::class,[
            'content' => '@Jane @Luke please help me!',
        ]);

        self::assertEquals(['Jane','Luke'],$question->invitedUsers());
    }

    /** @test */
    public function question_has_answers_count()
    {
        $question = create(Question::class);
        create(Answer::class,['question_id' => $question->id]);
        self::assertEquals(1,$question->refresh()->answers_count);
    }

    /** @test */
    public function question_can_be_subscribe_to()
    {
        $user = create(User::class);
        $question = create(Question::class,['user_id' => $user->id]);
        $question->subscribe($user);

        self::assertEquals(1,$question->subscriptions()->where('user_id',$user->id)->count());
    }

    /** @test */
    public function question_can_be_unsubscribed_from()
    {
        $user = create(User::class);
        $question = create(Question::class,['user_id' => $user->id]);
        $question->subscribe($user);
        $question->unsubscribe($user);

        self::assertEquals(0,$question->subscriptions()->where('user_id',$user->id)->count());
    }

    /** @test */
    public function question_can_add_answer()
    {
        $question = create(Question::class);
        $question->addAnswer([
            'content' => create(Answer::class)->content,
            'user_id' => create(User::class)->id,
        ]);

        self::assertEquals(1,$question->refresh()->answers()->count());
    }
}
