<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Answer extends Model
{
    protected $guarded = [
        'id',
    ];

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function votes($type)
    {
        return $this->morphMany(Vote::class,'voted')->whereType($type);
    }

    public function isBest(): bool
    {
        return (int)$this->question->best_answer_id === (int)$this->id;
    }

    public function voteUp($user)
    {
        $this->votes('vote_up')->create(['user_id' => $user->id,'type' => 'vote_up']);
    }

    public function cancelVoteUp($user)
    {
        $this->votes('vote_up')->where(['user_id' => $user->id,'type' => 'vote_up'])->delete();
    }
}
