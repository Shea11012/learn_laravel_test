<?php

namespace App\Models;

use App\Models\Traits\InvitedUsersTrait;
use App\Models\Traits\VoteTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Comment extends Model
{
    use VoteTrait,InvitedUsersTrait;
    protected $guarded = ['id'];

    protected $appends = [
        'upVotesCount',
        'downVotesCount',
    ];

    public function commented(): MorphTo
    {
        return $this->morphTo();
    }

    public function owner()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
