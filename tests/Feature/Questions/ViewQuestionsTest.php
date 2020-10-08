<?php

namespace Tests\Feature\Questions;

use App\Models\Answer;
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
        $this->get(route('questions.show',['question' => $question]))
            ->assertStatus(200)
            ->assertSee($question->title)
            ->assertSee($question->content);
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

        $test = $this->get(route('questions.show',['question' => $question]));

        $test->assertStatus(200)
            ->assertSee($question->title)
            ->assertSee($question->content);
    }

    /** @test */
    public function can_see_answers_when_view_a_published_question()
    {
        $question = factory(Question::class)->states('published')->create();
        create(Answer::class,['question_id' => $question->id],40);

        $response = $this->get(route('questions.show',['question' => $question]));

        $result = $response->jsonData('answers')->toArray();
        self::assertCount(20,$result['data']);
        self::assertEquals(40,$result['total']);

    }
}
