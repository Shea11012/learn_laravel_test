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

    /** @test */
    public function a_user_can_fetch_their_unread_notifications()
    {
        $this->signIn();
        /** @var Question $question */
        $question  = create(Question::class,[
            'user_id' => \Auth::id(),
        ]);

        $question->subscribe(\Auth::user());

        $question->addAnswer([
            'user_id' => create(User::class)->id,
            'content' => 'some reply here',
        ]);

        $response = $this->get(route('user-notifications.index',['user' => \Auth::user()]));
        $result = $response->json();
        self::assertCount(1,$result['data']);
        self::assertEquals(1,$result['total']);
    }

    /** @test */
    public function clear_all_unread_notifications_after_see_unread_notifications_page()
    {
        $this->signIn();
        $question = create(Question::class,[
            'user_id' => \Auth::id(),
        ]);

        $question->subscribe(\Auth::user());

        $question->addAnswer([
            'user_id' => create(User::class)->id,
            'content' => 'some reply here',
        ]);

        self::assertCount(1,\Auth::user()->fresh()->unreadNotifications);

        $this->get(route('user-notifications.index',[\Auth::user()]));

        self::assertCount(0,\Auth::user()->fresh()->unreadNotifications);
    }
}
