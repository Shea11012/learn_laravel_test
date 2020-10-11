<?php


namespace Comments;


use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\VoteUpContractTest;
use Tests\TestCase;

class UpVotesTest extends TestCase
{
    use RefreshDatabase;
    use VoteUpContractTest;

    protected function upVotes($comment)
    {
        return $comment->refresh()->votes('vote_up')->get();
    }

    protected function getModel()
    {
        return Comment::class;
    }

    protected function getVoteUpStoreUri($comment = null)
    {
        return $comment ? route('comment-up-votes.store',[$comment]) : route('comment-up-votes.store',[1]);
    }

    protected function getVoteUpDestroyUri($comment = null)
    {
        return $comment ? route('comment-up-votes.destroy',[$comment]) : route('comment-up-votes.destroy',[1]);
    }
}