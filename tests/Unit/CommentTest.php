<?php

namespace Tests\Unit;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_comment_has_morph_to_attribute()
    {
        $comment = create(Comment::class);
        self::assertInstanceOf(MorphTo::class,$comment->commented());
    }

    /** @test */
    public function a_comment_belongs_to_a_user()
    {
        $comment = create(Comment::class);
        self::assertInstanceOf(BelongsTo::class,$comment->owner());
        self::assertInstanceOf(User::class,$comment->owner);
    }
}
