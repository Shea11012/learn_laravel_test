<?php

namespace App\Models;

use App\Models\Traits\VoteTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Answer extends Model
{
    use VoteTrait;
    protected $guarded = [
        'id',
    ];

    protected $appends = [
        'upVotesCount',
        'downVotesCount',
        'commentsCount',
    ];

    protected static function boot()
    {
        parent::boot();

        static::created(static function ($answer) {
            $answer->question->increment('answers_count');
        });
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function comments()
    {
        return $this->morphMany(Comment::class,'commented');
    }

    public function getCommentsCountAttribute()
    {
        return $this->comments()->count();
    }

    public function comment($content,$user)
    {
        return $this->comments()->create([
            'user_id' => $user->id,
            'content' => $content,
        ]);
    }

    public function isBest(): bool
    {
        return (int)$this->question->best_answer_id === (int)$this->id;
    }
}
