<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;

class AdminMiddleware
{
    use ApiResponse;

    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        if (!$user || !$user->is_admin) {
            return $this->error('Unauthorized. Admin access required.', 403);
        }

        return $next($request);
    }
}