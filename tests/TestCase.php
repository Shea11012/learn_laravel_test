<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\TestResponse;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp()
    {
        parent::setUp();
        $this->withoutExceptionHandling();
        TestResponse::macro('jsonData',function ($key) {
            return $this->original[$key];
        });
    }

    protected function signIn($user = null,$guard = 'api'): TestCase
    {
        $user = $user ?: create(User::class);
        $this->actingAs($user,$guard);
        return $this;
    }

    protected function assertResponseSuccess($response,$content)
    {
        $response->assertStatus(200);
        $response->assertJson($content);
    }
}
