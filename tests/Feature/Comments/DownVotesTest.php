<?php


namespace Comments;


use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\VoteDownContractTest;
use Tests\TestCase;

class DownVotesTest extends TestCase
{
    use RefreshDatabase,VoteDownContractTest;

    protected function getVoteDownStoreUri($comment = null)
    {
        return $comment ? route('comment-down-votes.store',[$comment]) : route('comment-down-votes.store',[1]);
    }

    protected function getVoteDownDestroyUri($comment = null)
    {
        return $comment ? route('comment-down-votes.destroy',[$comment]) : route('comment-down-votes.destroy',[1]);
    }

    protected function upVotes($comment)
    {
        return $comment->refresh()->votes('vote_down')->get();
    }

    protected function getModel()
    {
        return Comment::class;
    }
}