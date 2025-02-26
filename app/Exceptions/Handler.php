<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    protected $dontReport = [
        AuthenticationException::class,
        ValidationException::class,
    ];

    public function register(): void
    {
        // Business Logic Exceptions
        $this->renderable(function (\InvalidArgumentException $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
        });

        // Validation Exceptions
        $this->renderable(function (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        });

        // Authentication Exceptions
        $this->renderable(function (AuthenticationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ], 401);
        });

        // Authorization Exceptions
        $this->renderable(function (AuthorizationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Forbidden'
            ], 403);
        });

        // Model Not Found Exceptions
        $this->renderable(function (ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Resource not found'
            ], 404);
        });

        // Route Not Found Exceptions
        $this->renderable(function (NotFoundHttpException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'The requested endpoint was not found'
            ], 404);
        });

        // Method Not Allowed Exceptions
        $this->renderable(function (MethodNotAllowedHttpException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Method not allowed'
            ], 405);
        });

        // Generic API Exception Handler
        $this->renderable(function (Throwable $e) {
            if (request()->is('api/*')) {
                $response = [
                    'status' => 'error',
                    'message' => 'An unexpected error occurred'
                ];

                if (config('app.debug')) {
                    $response['debug'] = [
                        'message' => $e->getMessage(),
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                        'trace' => $e->getTrace()
                    ];
                }

                return response()->json($response, 500);
            }
        });
    }
}