<?php

namespace Tests\Unit;

use App\Models\Answer;
use App\Models\Question;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuestionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_question_has_many_answers()
    {
        $question = factory(Question::class)->create();
        factory(Answer::class)->create(['question_id' => $question->id]);

        self::assertInstanceOf(HasMany::class,$question->answers());
    }
}
