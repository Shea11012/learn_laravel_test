<?php

namespace Tests;

use App\Models\User;
use App\Translator\FakeSlugTranslator;
use App\Translator\Translator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\JsonResponse;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use WithFaker;

    protected function setUp()
    {
        parent::setUp();
        $this->setUpFaker();
        $this->withoutExceptionHandling();
        TestResponse::macro('jsonData', function ($key) {
            if ($this->baseResponse instanceof JsonResponse) {
                return $this->getOriginalContent()['data'][$key];
            }
            return $this->getOriginalContent()[$key];
        });

        $this->app->instance(Translator::class,new FakeSlugTranslator);
    }

    protected function signIn($user = null, $guard = 'api'): TestCase
    {
        $user = $user ?: create(User::class);
        $this->actingAs($user, $guard);
        return $this;
    }

    protected function assertResponseSuccess($response, $content)
    {
        $response->assertStatus(200);
        $response->assertJson($content);
    }
}
