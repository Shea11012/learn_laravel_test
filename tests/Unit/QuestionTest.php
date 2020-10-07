<?php

namespace Tests\Unit;

use App\Models\Answer;
use App\Models\Question;
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
}
