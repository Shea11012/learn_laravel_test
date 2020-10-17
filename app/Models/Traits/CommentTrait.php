<?php


namespace App\Models\Traits;


use App\Event\PostComment;
use App\Models\Comment;
use Illuminate\Support\Str;

trait CommentTrait
{
    public function comment($content, $user)
    {
        $comment = $this->comments()->create([
            'user_id' => $user->id,
            'content' => $content,
        ]);

        event(new PostComment($comment));

        return $comment;
    }

    public function comments()
    {
        return $this->morphMany(Comment::class,'commented');
    }

    public function getCommentsCountAttribute()
    {
        return $this->comments()->count();
    }

    public function getCommentEndpointAttribute()
    {
        $table = Str::lower(class_basename(static::class));
        return route($table.'-comments.index',[$this->id]);
    }
}