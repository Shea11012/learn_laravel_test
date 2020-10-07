<?php

namespace Tests\Feature\Answers;

use App\Models\Question;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Auth\AuthenticationException;

class PostAnswersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_may_not_post_an_answer()
    {
//        $this->withExceptionHandling();
        $this->expectException(AuthenticationException::class);
        $question = factory(Question::class)->states('published')->create();
        $this->post(route('answers.store',['question' => $question->id],false),[
            'content' => 'This is an answer',
        ]);

//        $response->assertStatus(500);
    }

    /** @test */
    public function signed_in_user_can_post_an_answer_to_a_published_question()
    {
        $question = factory(Question::class)->states('published')->create();
        $this->signIn($user = create(User::class));

        $response = $this->post("/api/v1/questions/{$question->id}/answers",[
            'user_id' => $user->id,
            'content' => 'This is an answer'
        ]);

        $response->assertStatus(201);

        $answer = $question->answers()->where('user_id',$user->id)->first();
        self::assertNotNull($answer);
        self::assertEquals(1,$question->answers()->count());
    }

    /** @test */
    public function can_not_post_an_answer_to_an_unpublished_question()
    {
        $question = factory(Question::class)->states('unpublished')->create();
        $this->signIn($user = create(User::class));

        $response = $this->withExceptionHandling()
            ->post("/api/v1/questions/{$question->id}/answers",[
                'user_id' => $user->id,
                'content' => 'This is an answer',
            ]);

        $response->assertStatus(404);
        $this->assertDatabaseMissing('answers',['question_id' => $question->id]);
        self::assertEquals(0,$question->answers()->count());
    }

    /** @test */
    public function content_is_required_to_post_answers()
    {
        $question = factory(Question::class)->states('published')->create();
        $this->signIn($user = create(User::class));

        $response = $this->withExceptionHandling()->post("/api/v1/questions/{$question->id}/answers",[
            'user_id' => $user->id,
            'content' => null,
        ]);

        $response->assertJsonValidationErrors('content');
    }
}
