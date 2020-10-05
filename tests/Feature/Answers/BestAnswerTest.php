<?php

namespace Tests\Feature\Answers;

use App\Models\Answer;
use App\Models\Question;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BestAnswerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_can_not_mark_best_answer()
    {
        $this->expectException(AuthenticationException::class);
        $question = create(Question::class);
        $answers = create(Answer::class,['question_id' => $question->id],2);
        $this->post(route('best-answers.store',['answer' => $answers[1]]),[$answers[1]]);
    }

    /** @test */
    public function can_mark_one_answer_as_the_best()
    {
        $this->signIn();
        $question = create(Question::class,['user_id' => auth()->id()]);
        $answers = create(Answer::class,['question_id' => $question->id],2);
        self::assertFalse($answers[0]->isBest());
        self::assertFalse($answers[1]->isBest());

        $response = $this->postJson(route('best-answers.store',['answer' => $answers[1]]),[$answers[1]]);

        $response->assertStatus(200);
        $response->assertJson(['code' => 200]);
    }

    /** @test */
    public function only_the_question_creator_can_mark_a_best_answer()
    {
        $this->withExceptionHandling();
        $this->signIn();
        $question = create(Question::class,['user_id' => auth()->id()]);
        $answer = create(Answer::class,['question_id' => $question->id]);

        // 另外一个用户
        $this->signIn(create(User::class));
        $response = $this->postJson(route('best-answers.store',['answer' =>$answer]),[$answer]);

        $response->assertStatus(403);
        self::assertFalse($answer->fresh()->isBest());
    }
}
