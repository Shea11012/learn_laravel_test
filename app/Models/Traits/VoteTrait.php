<?php


namespace App\Models\Traits;


use App\Models\Vote;

trait VoteTrait
{
    public function voteUp($user)
    {
        $this->vote('vote_up',$user);
    }

    public function voteDown($user)
    {
        $this->vote('vote_down',$user);
    }

    public function cancelVoteUp($user)
    {
        $this->votes('vote_up')->where(['user_id' => $user->id,'type' => 'vote_up'])->delete();
    }

    public function cancelVoteDown($user)
    {
        $this->votes('vote_down')->where(['user_id' => $user->id])->delete();
    }

    public function isVotedUp($user)
    {
        if (!$user) {
            return false;
        }
        return $this->votes('vote_up')->where(['user_id' => $user->id])->exists();
    }

    public function isVotedDown($user)
    {
        if (!$user) {
            return false;
        }

        return $this->votes('vote_down')->where(['user_id' => $user->id])->exists();
    }

    public function getUpVotesCountAttribute()
    {
        return $this->votes('vote_up')->count();
    }

    public function getDownVotesCountAttribute()
    {
        return $this->votes('vote_down')->count();
    }

    public function votes($type)
    {
        return $this->morphMany(Vote::class,'voted')->whereType($type);
    }

    protected function vote($type,$user)
    {
        if (!$this->votes($type)->where('user_id',$user->id)->exists()) {
            $this->votes($type)->create(['user_id' => $user->id,'type' => $type]);
        }
    }
}