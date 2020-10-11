<?php

namespace Tests\Feature\Comments;

use App\Models\Answer;
use Illuminate\Auth\AuthenticationException;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AnswerCommentsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_may_not_comment_an_answer()
    {
        $this->expectException(AuthenticationException::class);

        $answer = create(Answer::class);

        $this->post(route('answer-comments.store',['answer' => $answer]),[
            'content' => 'This is a comment',
        ]);
    }

    /** @test */
    public function signed_in_user_can_comment_an_answer()
    {
        $answer = create(Answer::class);
        $this->signIn();
        $response = $this->post(route('answer-comments.store',['answer' => $answer]),[
            'content' => 'This is a comment',
        ]);

        $response->assertStatus(200);

        $comment = $answer->comments()->where('user_id',\Auth::id())->first();

        self::assertNotNull($comment);
        self::assertEquals(1,$answer->comments()->count());
    }

    /** @test */
    public function content_is_required_to_comment_an_answer()
    {
        $answer = create(Answer::class);
        $this->signIn();
        $this->withExceptionHandling();
        $response = $this->post(route('answer-comments.store',[$answer]),[
            'content' => null,
        ]);

        $response->assertJsonValidationErrors('content');
    }
}