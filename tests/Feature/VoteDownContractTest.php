<?php
namespace Tests\Feature;

use Illuminate\Auth\AuthenticationException;

trait VoteDownContractTest
{
    /** @test */
    public function guest_can_not_vote_down()
    {
        $this->expectException(AuthenticationException::class);
        $model = create($this->getModel());
        $this->post($this->getVoteDownStoreUri($model));
    }

    /** @test */
    public function authenticated_user_can_vote_down()
    {
        $this->signIn();
        $model = create($this->getModel());
        $response = $this->post($this->getVoteDownStoreUri($model));
        $this->assertResponseSuccess($response,['code' => 200]);

        self::assertCount(1,$this->upVotes($model));
    }

    /** @test */
    public function an_authenticated_user_can_cancel_vote_down()
    {
        $this->signIn();
        $model = create($this->getModel());
        $this->post($this->getVoteDownStoreUri($model));
        self::assertCount(1,$this->upVotes($model));

        $this->delete($this->getVoteDownDestroyUri($model));
        self::assertCount(0,$this->upVotes($model));
    }

    /** @test */
    public function can_know_it_is_voted_down()
    {
        $this->signIn();
        $model = create($this->getModel());
        $this->post($this->getVoteDownDestroyUri($model));
        self::assertTrue($model->refresh()->isVotedDown(\Auth::user()));
    }

    /** @test */
    public function can_know_down_votes_count()
    {
        $this->signIn();
        $model = create($this->getModel());
        $this->post($this->getVoteDownStoreUri($model));
        self::assertEquals(1,$model->refresh()->downVotesCount);

        $this->signIn();
        $this->post($this->getVoteDownStoreUri($model));
        self::assertEquals(2,$model->refresh()->downVotesCount);
    }

    abstract protected function getVoteDownStoreUri($model = null);
    abstract protected function getVoteDownDestroyUri($model = null);
    abstract protected function upVotes($model);
    abstract protected function getModel();
}