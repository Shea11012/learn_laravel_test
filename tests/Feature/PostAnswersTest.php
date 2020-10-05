<?php

namespace Tests\Feature;

use App\Models\Question;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostAnswersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function signed_in_user_can_post_an_answer_to_a_published_question()
    {
        $question = factory(Question::class)->states('published')->create();
        $this->actingAs($user = factory(User::class)->create(),'api');

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
        $user = factory(User::class)->create();

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
        $user = factory(User::class)->create();

        $response = $this->withExceptionHandling()->post("/api/v1/questions/{$question->id}/answers",[
            'user_id' => $user->id,
            'content' => null,
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('content');
    }
}
