<?php


namespace Tests\Feature;


use Illuminate\Auth\AuthenticationException;

trait VoteUpContractTest
{
    /** @test */
    public function guest_can_not_vote_up()
    {
        $this->expectException(AuthenticationException::class);
        $model = create($this->getModel());
        $this->post($this->getVoteUpStoreUri($model));
    }

    /** @test */
    public function authenticate_user_can_vote_up()
    {
        $this->signIn();
        $model = create($this->getModel());
        $response = $this->post($this->getVoteUpStoreUri($model));
        $this->assertResponseSuccess($response,['code' => 200]);

        self::assertCount(1,$this->upVotes($model));
    }

    /** @test */
    public function an_authenticated_user_can_cancel_vote_up()
    {
        $this->signIn();
        $model = create($this->getModel());
        $this->post($this->getVoteUpStoreUri($model));

        self::assertCount(1,$this->upVotes($model));

        $this->delete($this->getVoteUpDestroyUri($model));

        self::assertCount(0,$this->upVotes($model));
    }

    /** @test */
    public function can_vote_up_only_once()
    {
        $this->signIn();

        $model = create($this->getModel());

        try {
            $this->post($this->getVoteUpDestroyUri($model));
            $this->post($this->getVoteUpDestroyUri($model));
        } catch (\Exception $exception) {
            self::fail('Can not vote up twice.');
        }

        self::assertCount(1,$model->refresh()->votes('vote_up')->get());
    }

    /** @test */
    public function can_know_it_is_voted_up()
    {
        $this->signIn();

        $model = create($this->getModel());
        $this->post($this->getVoteUpStoreUri($model));
        self::assertTrue($model->fresh()->isVotedUp(\Auth::user()));
    }

    /** @test */
    public function can_know_up_votes_count()
    {
        $this->signIn();
        $model = create($this->getModel());
        $this->post($this->getVoteUpStoreUri($model));
        self::assertEquals(1,$model->refresh()->upVotesCount);

        // 另外一个用户
        $this->signIn();
        $this->post($this->getVoteUpStoreUri($model));
        self::assertEquals(2,$model->refresh()->upVotesCount);
    }

    abstract protected function getVoteUpStoreUri($model = null);
    abstract protected function getVoteUpDestroyUri($model = null);
    abstract protected function upVotes($model);
    abstract protected function getModel();
}