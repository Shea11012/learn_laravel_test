<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

class MustVerifyEmail
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, $next)
    {
        if (null === Auth::user()->email_verified_at) {
            return response()->json([
                'code' => 200,
                'message' => 'please verify your email',
            ]);
        }
        return $next($request);
    }
}
