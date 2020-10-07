<?php


namespace Http\Middleware;


use App\Http\Middleware\MustVerifyEmail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;

class MustVerifyEmailTest extends \Tests\TestCase
{
    use RefreshDatabase;

    /** @test */
    public function verified_user_can_continue()
    {
        $this->signIn(create(User::class,['email_verified_at' => Carbon::now()]));

        $request = new Request();

        // 以调用函数的方式调用对象时，__invoke 会被自动调用，可以用来测试闭包函数是否被调用
        $next = new class {
            public $called = false;

            public function __invoke($request)
            {
                $this->called = true;
                return $request;
            }
        };

        $middleware = new MustVerifyEmail();

        $response = $middleware->handle($request,$next);
        self::assertTrue($next->called);
        self::assertSame($request,$response);
    }
}