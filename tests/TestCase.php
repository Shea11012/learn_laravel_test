<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp()
    {
        parent::setUp();
        $this->withoutExceptionHandling();
    }

    protected function signIn($user = null,$guard = 'api'): TestCase
    {
        $user = $user ?: create(User::class);
        $this->actingAs($user,$guard);
        return $this;
    }
}
