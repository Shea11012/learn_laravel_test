<?php

namespace App\Models;

use App\Models\Traits\CommentTrait;
use App\Models\Traits\InvitedUsersTrait;
use App\Models\Traits\RecordActivityTrait;
use App\Models\Traits\VoteTrait;
use App\Notifications\QuestionWasUpdated;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    use VoteTrait;
    use CommentTrait,InvitedUsersTrait,RecordActivityTrait;
    protected $guarded = ['id'];
    protected $with = ['category'];

    protected $appends = [
        'upVotesCount',
        'downVotesCount',
        'subscriptionsCount',
        'commentsCount',
        'commentEndpoint',
    ];

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

    public function getSubscriptionsCountAttribute()
    {
        return $this->subscriptions->count();
    }

    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function publish()
    {
        $this->update([
            'published_at' => Carbon::now(),
        ]);
    }

    public function subscribe($user)
    {
        $this->subscriptions()->create([
            'user_id' => $user->id,
        ]);

        return $this;
    }

    public function unsubscribe($user)
    {
        $this->subscriptions()->where('user_id',$user->id)->delete();
        return $this;
    }

    public function addAnswer($answer)
    {
        $answer = $this->answers()->create($answer);

        $this->subscriptions
            ->where('user_id','!=',$answer->user_id)
            ->each
            ->notify($answer);

        return $answer;
    }

    public function isSubscribedTo($user)
    {
        if (!$user) {
            return false;
        }
        return $this->subscriptions()->where('user_id',$user->id)->exists();
    }

    public function path()
    {
        return route('questions.show',[$this->category->slug, $this, $this->slug ?: null]);
    }
}
