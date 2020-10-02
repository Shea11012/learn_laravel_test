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
    public function user_can_post_an_answer_to_a_question()
    {
        $question = factory(Question::class)->create();
        $user = factory(User::class)->create();

        $response = $this->post("/questions/{$question->id}/answers",[
            'user_id' => $user->id,
            'content' => 'This is an answer'
        ]);

        $response->assertStatus(201);

        $answer = $question->answers()->where('user_id',$user->id)->first();
        self::assertNotNull($answer);
        self::assertEquals(1,$question->answers()->count());
    }
}
