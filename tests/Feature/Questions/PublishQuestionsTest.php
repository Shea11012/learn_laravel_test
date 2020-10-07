<?php

namespace Tests\Feature\Questions;

use App\Models\Question;
use Illuminate\Auth\AuthenticationException;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PublishQuestionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_may_not_publish_questions()
    {
        $this->expectException(AuthenticationException::class);
        $this->post(route('published-questions.store',['question' => 1]));
    }

    /** @test */
    public function can_publish_question()
    {
        $this->signIn();
        $question = create(Question::class,['user_id' => auth()->id()]);

        self::assertCount(0,Question::published()->get());

        $this->post(route('published-questions.store',['question' => $question]));
        self::assertCount(1,Question::published()->get());
    }

    /** @test */
    public function only_the_question_creator_can_publish_it()
    {
        $this->withExceptionHandling();
        $this->signIn();
        $question = create(Question::class,['user_id' => auth()->id()]);

        $this->signIn();
        $this->post(route('published-questions.store',['question' => $question]));

        self::assertCount(0,Question::published()->get());
    }
}
