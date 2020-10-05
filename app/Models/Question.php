<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $guarded = ['id'];
    public function answers(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Answer::class);
    }

    public function scopePublished($query)
    {
        return $query->whereNotNull('published_at');
    }

    public function markAsBestAnswer($answer)
    {
        $this->update([
            'best_answer_id' => $answer->id,
        ]);
    }
}
