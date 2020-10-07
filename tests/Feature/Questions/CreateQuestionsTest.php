<?php

namespace Tests\Feature\Questions;

use App\Models\Category;
use App\Models\Question;
use Illuminate\Auth\AuthenticationException;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateQuestionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_may_not_create_questions()
    {
        $this->expectException(AuthenticationException::class);
        $this->post(route('questions.store'),[]);
    }

    /** @test */
    public function an_authenticated_user_can_create_new_questions()
    {
        $this->signIn();
        $question = make(Question::class);
        self::assertCount(0,Question::all());

        $this->post(route('questions.store',$question->toArray()));
        self::assertCount(1,Question::all());
    }

    /** @test */
    public function title_is_required()
    {
        $this->signIn();
        $this->withExceptionHandling();
        $response = $this->post(route('questions.store'),['title' => null]);

        $response->assertJsonValidationErrors('title');
    }

    /** @test */
    public function content_is_required()
    {
        $this->signIn();
        $this->withExceptionHandling();
        $response = $this->post(route('questions.store'),['content' => null]);

        $response->assertJsonValidationErrors('content');
    }

    /** @test  */
    public function category_id_is_required()
    {
        $this->signIn();
        $this->withExceptionHandling();
        $response = $this->post(route('questions.store'),['category_id' => null]);
        $response->assertJsonValidationErrors('category_id');
    }

    /** @test */
    public function category_id_is_existed()
    {
        $this->signIn()->withExceptionHandling();

        create(Category::class,['id' => 1]);

        $question = make(Question::class,['category_id' => 999]);

        $response = $this->post(route('questions.store'),$question->toArray());

        $response->assertJsonValidationErrors('category_id');
    }
}
