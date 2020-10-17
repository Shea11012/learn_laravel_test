<?php

namespace Tests\Feature\Comments;

use App\Models\Question;
use App\Models\User;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MentionUsersTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function mentioned_users_are_notified_when_comment_a_question()
    {
        $john = create(User::class,['name' => 'John']);
        $jane = create(User::class,['name' => 'Jane']);
        $foo = create(User::class,['name' => 'Foo']);

        $this->signIn($john);

        $question = create(Question::class,['published_at' => Carbon::now()]);

        self::assertCount(0,$jane->notifications);
        self::assertCount(0,$foo->notifications);

        $this->postJson(route('question-comments.store',[$question]),[
            'content' => '@Jane @Foo please help me!',
        ]);

        self::assertCount(1,$jane->refresh()->notifications);
        self::assertCount(1,$foo->refresh()->notifications);
    }
}
