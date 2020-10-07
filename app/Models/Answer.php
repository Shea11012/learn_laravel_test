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
