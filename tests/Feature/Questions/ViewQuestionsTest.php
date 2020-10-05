<?php

namespace Tests\Feature\Questions;

use App\Models\Question;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ViewQuestionsTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function user_can_view_questions()
    {
        $this->withoutExceptionHandling();
        $test = $this->get('/api/v1/questions');

        $test->assertStatus(200);
    }

    /** @test */
    public function user_can_view_a_published_question()
    {
        $question = factory(Question::class)->create(['published_at' => Carbon::parse('-1 week')]);
        $this->get('/api/v1/questions/'.$question->id)
            ->assertStatus(200)
            ->assertJson([
                'title' => $question->title,
                'content' => $question->content,
            ]);
    }

    /** @test */
    public function user_cannot_view_unpublished_question()
    {
        $question = factory(Question::class)->create(['published_at' => null]);
        $this->withExceptionHandling()->get('/api/v1/questions/'.$question->id)
            ->assertStatus(404);
    }

    /** @test */
    public function user_can_view_a_single_question()
    {
        $question = factory(Question::class)->states('published')->create();

        $test = $this->get('/api/v1/questions/'.$question->id);

        $test->assertStatus(200)
            ->assertJson([
                'title' => $question->title,
                'content' => $question->content,
            ]);
    }
}
