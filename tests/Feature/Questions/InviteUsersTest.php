<?php

namespace Tests\Feature\Questions;

use App\Models\Question;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class InviteUsersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function invited_users_are_notified_when_publish_a_question()
    {
        $john = create(User::class,['name' => 'John']);
        $jane = create(User::class,['name' => 'Jane']);

        $this->signIn($john);
        $question = create(Question::class,[
            'user_id' => $john->id,
            'content' => '@Jane please help me!',
            'published_at' => null,
        ]);

        self::assertCount(0,$jane->notifications);

        $this->postJson(route('published-questions.store',['question' => $question]));

        self::assertCount(1,$jane->refresh()->notifications);
    }

    /** @test */
    public function all_invited_users_are_notified()
    {
        $john = create(User::class,['name' => 'John']);
        $jane = create(User::class,['name' => 'Jane']);
        $foo  = create(User::class,['name' => 'Foo']);

        $this->signIn($john);

        $question = create(Question::class,[
            'user_id' => $john->id,
            'content' => '@Jane @Foo please help me!'
        ]);

        self::assertCount(0,$jane->notifications);
        self::assertCount(0,$foo->notifications);

        $this->postJson(route('published-questions.store',['question' => $question]));

        self::assertCount(1,$jane->refresh()->notifications);
        self::assertCount(1,$foo->refresh()->notifications);
    }
}
