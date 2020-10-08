<?php

namespace App\Models;

use App\Models\Traits\VoteTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    use VoteTrait;
    protected $guarded = ['id'];
    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class);
    }

    public function scopePublished($query)
    {
        return $query->whereNotNull('published_at');
    }

    public function scopeFilter($query,$filters)
    {
        return $filters->apply($query);
    }

    public function markAsBestAnswer($answer)
    {
        $this->update([
            'best_answer_id' => $answer->id,
        ]);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function publish()
    {
        $this->update([
            'published_at' => Carbon::now(),
        ]);
    }

    public function invitedUsers()
    {
        preg_match_all('#@([^\s.]+)#',$this->content,$matches);
        return $matches[1];
    }
}
