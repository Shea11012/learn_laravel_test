<?php

namespace Tests\Feature\Questions;

use App\Models\Answer;
use App\Models\Category;
use App\Models\Question;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FilterQuestionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_see_published_questions_without_any_filter()
    {
        create(Question::class, ['published_at' => Carbon::now()],10);
        $unpublishedQuestion = create(Question::class);

        create(Question::class,['published_at' => Carbon::now()],30);

        $publishedQuestion = Question::find(1);

        $response = $this->get(route('questions.list'));

        $response->assertSee($publishedQuestion->title)
            ->assertDontSee($unpublishedQuestion->title);

        $result = $response->jsonData('questions')->toArray();

        self::assertEquals(40,$result['total']);
        self::assertCount(20,$result['data']);
    }

    /** @test */
    public function user_can_filter_questions_by_category()
    {
        $category = create(Category::class);
        $questionInCategory = $this->publishedQuestion(['category_id' => $category->id]);

        $questionNotInCategory = $this->publishedQuestion();
        $response = $this->get(route('questions.list',['category' => $category->slug]));
        $response->assertSee($questionInCategory->title)
            ->assertDontSee($questionNotInCategory->title);
    }

    /** @test */
    public function user_can_filter_questions_by_username()
    {
        $john = create(User::class,['name' => 'john']);
        $this->signIn($john);
        $questionByJohn = $this->publishedQuestion(['user_id' => $john->id]);
        $questionNotByJohn = $this->publishedQuestion();
        $this->get(route('questions.list',[null,'by' => 'john']))
            ->assertSee($questionByJohn->title)
            ->assertDontSee($questionNotByJohn->title);
    }

    /** @test */
    public function user_can_filter_questions_by_popularity()
    {
        // question without answers
        $this->publishedQuestion();

        // question with two answers
        $questionOfTwoAnswers = $this->publishedQuestion();
        create(Answer::class,['question_id' => $questionOfTwoAnswers->id],2);

        // question with three answers
        $questionOfTwoAnswers = $this->publishedQuestion();
        create(Answer::class,['question_id' => $questionOfTwoAnswers->id],3);

        $response = $this->get(route('questions.list',[null,'popularity' => 1]));
        $questions = $response->jsonData('questions')->items();

        self::assertEquals([3,2,0],array_column($questions,'answers_count'));
    }

    /** @test */
    public function a_user_can_filter_unanswered_questions()
    {
        $this->publishedQuestion();
        $questionOfTwoAnswers = $this->publishedQuestion();
        create(Answer::class,['question_id' => $questionOfTwoAnswers->id],2);

        $response = $this->get(route('questions.list',[null,'unanswered' => 1]));
        $result = $response->jsonData('questions')->toArray();

        self::assertEquals(1,$result['total']);
    }

    private function publishedQuestion($attributes = [])
    {
        return factory(Question::class)->states('published')->create($attributes);
    }
}
