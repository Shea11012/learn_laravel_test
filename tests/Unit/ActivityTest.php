<?php
namespace Tests\Unit;

use App\Models\Activity;
use App\Models\Answer;
use App\Models\Question;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ActivityTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_records_activity_when_a_question_is_published()
    {
        $user = create(User::class);
        $question = create(Question::class,['user_id' => $user->id]);
        $question->publish();

        $this->assertDatabaseHas('activities',[
            'type' => 'published_question',
            'user_id' => $user->id,
            'subject_id' => $question->id,
            'subject_type' => Question::class,
        ]);

        self::assertEquals(1,Activity::count());
    }

    /** @test */
    public function not_record_activity_when_a_question_mark_an_answer_as_best()
    {
        $user = create(User::class);
        /** @var Question $question */
        $question = create(Question::class,[
            'published_at' => Carbon::parse('-1 week'),
            'user_id' => $user->id,
        ]);

        $answer = create(Answer::class,[
            'question_id' => $question->id,
        ]);

        self::assertEquals(1,Activity::count());
        $question->markAsBestAnswer($answer);

        $this->assertDatabaseMissing('activities',[
            'type' => 'published_question',
            'user_id' => $user->id,
            'subject_id' => $question->id,
            'subject_type' => Question::class,
        ]);

        self::assertEquals(1,Activity::count());
    }

    /** @test */
    public function an_activity_belongs_to_a_subject()
    {
        $activity = create(Activity::class);
        self::assertInstanceOf(BelongsTo::class,$activity->subject());
    }

    /** @test */
    public function it_records_activity_when_an_answer_is_created()
    {
        $user = create(User::class);
        $answer = create(Answer::class,['user_id' => $user->id]);
        $this->assertDatabaseHas('activities',[
            'type' => 'created_answer',
            'user_id' => $user->id,
            'subject_id' => $answer->id,
            'subject_type' => get_class($answer),
        ]);

        $activity = Activity::first();

        self::assertEquals($activity->subject->id,$answer->id);
    }
}