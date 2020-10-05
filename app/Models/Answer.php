<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $guarded = [
        'id',
    ];

    public function question(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    public function isBest(): bool
    {
        return $this->question->best_answer_id === $this->id;
    }
}
