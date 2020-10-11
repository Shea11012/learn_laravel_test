<?php

namespace Tests\Feature\Comments;

use App\Models\Question;
use Illuminate\Auth\AuthenticationException;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class QuestionCommentsTest extends TestCase
{
    use RefreshDatabase;

    /** @test  */
    public function guests_may_not_comment_a_question()
    {
        $this->expectException(AuthenticationException::class);

        $question = factory(Question::class)->states('published')->create();

        $this->post(route('question-comments.store',['question' => $question]),[
            'content' => 'This is a comment',
        ]);
    }

    /** @test */
    public function can_not_comment_an_unpublished_question()
    {
        $question = factory(Question::class)->states('unpublished')->create();

        $this->signIn();
        $this->withExceptionHandling();
        $response = $this->post(route('question-comments.store',['question' => $question]),[
            'content' => 'This is a comment',
        ]);

        $response->assertStatus(404);
    }

    /** @test */
    public function signed_in_user_can_comment_a_published_question()
    {
        $question = factory(Question::class)->states('published')->create();
        $this->signIn();

        $response = $this->post(route('question-comments.store',['question' => $question]),[
            'content' => 'This is a comment',
        ]);

        $response->assertStatus(200);

        $comment = $question->comments()->where('user_id',\Auth::id())->first();

        self::assertNotNull($comment);

        self::assertEquals(1,$question->comments()->count());
    }

    /** @test */
    public function content_is_required_to_comment_a_question()
    {
        $question = factory(Question::class)->states('published')->create();
        $this->signIn();
        $this->withExceptionHandling();
        $response = $this->post(route('question-comments.store',['question' => $question]),[
            'content' => null,
        ]);

        $response->assertJsonValidationErrors('content');
    }
}
