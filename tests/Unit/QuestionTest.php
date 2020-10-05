<?php

namespace Tests\Unit;

use App\Models\Answer;
use App\Models\Question;
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

        self::assertInstanceOf(HasMany::class,$question->answers());
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
}
