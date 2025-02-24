<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class AddRateLimitHeaders
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if (!RateLimiter::tooManyAttempts('api:'.$request->ip(), 60)) {
            $headers = [
                'X-RateLimit-Limit' => 60,
                'X-RateLimit-Remaining' => RateLimiter::remaining('api:'.$request->ip(), 60),
            ];
            
            foreach ($headers as $key => $value) {
                $response->headers->set($key, $value);
            }
        }

        return $response;
    }
} 