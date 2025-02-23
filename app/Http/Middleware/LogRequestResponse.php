<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LogRequestResponse
{
    public function handle(Request $request, Closure $next)
    {
        // Request'i logla
        Log::info('API Request', [
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'headers' => $request->headers->all(),
            'body' => $request->all()
        ]);

        $response = $next($request);

        // Response'u logla
        Log::info('API Response', [
            'status' => $response->status(),
            'headers' => $response->headers->all(),
            'body' => $response->getContent()
        ]);

        return $response;
    }
} 