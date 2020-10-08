<?php

namespace Tests\Feature\Answers;

use App\Models\Answer;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteAnswersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_cannot_delete_answers()
    {
        $this->expectException(AuthenticationException::class);
        $model = create($this->getModel());
        $this->delete(route('answers.destroy',['answer' => $answer]),['answer' => $answer]);
    }

    /** @test */
    public function unauthorized_users_cannot_delete_answers()
    {
        $this->expectException(AuthorizationException::class);
        $this->signIn();
        $model = create($this->getModel());
        $response = $this->delete(route('answers.destroy',['answer' => $answer]));

        $response->assertStatus(200);
        $response->assertJson(['code' => 200]);
    }

    /** @test */
    public function authorized_users_can_delete_answers()
    {
        $this->signIn();
        $answer = create(Answer::class,['user_id' => auth()->id()]);
        $response = $this->delete(route('answers.destroy',['answer' => $answer]));

        $this->assertResponseSuccess($response,['code' => 200]);
    }
}
