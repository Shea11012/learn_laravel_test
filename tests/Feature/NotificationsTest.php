<?php

namespace Tests\Feature;

use App\Models\Question;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NotificationsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp()
    {
        parent::setUp();
        $this->signIn();
    }

    /** @test */
    public function a_notification_is_prepared_when_a_subscribed_question_receives_a_new_answer_by_other_people()
    {
        $question = create(Question::class,[
            'user_id' => auth()->id(),
        ]);

        $question->subscribe(\Auth::user());
        self::assertCount(0,\Auth::user()->notifications);

        $question->addAnswer([
            'user_id' => \Auth::id(),
            'content' => 'some reply here',
        ]);

        self::assertCount(0,\Auth::user()->refresh()->notifications);

        $question->addAnswer([
            'user_id' => create(User::class)->id,
            'content' => 'some reply here',
        ]);

        self::assertCount(1,\Auth::user()->refresh()->notifications);

    }
}
