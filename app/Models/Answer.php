<?php

namespace App\Models;

use App\Models\Traits\CommentTrait;
use App\Models\Traits\VoteTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Answer extends Model
{
    use VoteTrait,CommentTrait;
    protected $guarded = [
        'id',
    ];

    protected $appends = [
        'upVotesCount',
        'downVotesCount',
        'commentsCount',
        'commentEndpoint',
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

    public function isBest(): bool
    {
        return (int)$this->question->best_answer_id === (int)$this->id;
    }
}
