<?php

namespace Tests\Feature\Questions;

use App\Models\Question;
use Illuminate\Auth\AuthenticationException;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SubscribeQuestionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_may_not_subscribe_to_or_unsubscribe_from_questions()
    {
        $this->expectException(AuthenticationException::class);
        $question = create(Question::class);
        $this->post(route('subscribe-questions.store',['question' => $question]));
        $this->delete(route('subscribe-questions.destroy',['question' => $question]));
    }

    /** @test */
    public function a_user_can_subscribe_to_question()
    {
        $this->signIn();
        $question = factory(Question::class)->states('published')->create();
        $this->post(route('subscribe-questions.store',['question' => $question]));
        self::assertCount(1,$question->subscriptions);
    }

    /** @test */
    public function a_user_can_unsubscribe_from_question()
    {
        $this->signIn();

        $question = factory(Question::class)->states('published')->create();
        $this->post(route('subscribe-questions.store',['question' => $question]));
        $this->delete(route('subscribe-questions.destroy',['question' => $question]));

        self::assertCount(0,$question->subscriptions);
    }
}
