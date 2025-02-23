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
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        AuthenticationException::class,
        ValidationException::class,
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        // Validation hatası (422)
        $this->renderable(function (ValidationException $e) {
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => $e->errors(),
            ], 422);
        });

        // Authentication hatası (401)
        $this->renderable(function (AuthenticationException $e) {
            return response()->json([
                'message' => 'Unauthenticated.',
            ], 401);
        });

        // Authorization hatası (403)
        $this->renderable(function (AuthorizationException $e) {
            return response()->json([
                'message' => 'This action is unauthorized.',
            ], 403);
        });

        // Model bulunamadı (404)
        $this->renderable(function (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Resource not found.'
            ], 404);
        });

        // Route bulunamadı (404)
        $this->renderable(function (NotFoundHttpException $e) {
            return response()->json([
                'message' => 'The requested URL was not found.'
            ], 404);
        });

        // HTTP metodu izin verilmeyen (405)
        $this->renderable(function (MethodNotAllowedHttpException $e) {
            return response()->json([
                'message' => 'The requested method is not allowed.'
            ], 405);
        });

        // Diğer tüm hatalar (500)
        $this->renderable(function (Throwable $e) {
            if (request()->is('api/*')) {
                $response = [
                    'message' => 'Server error occurred.'
                ];

                // Geliştirme ortamında hata detaylarını göster
                if (config('app.debug')) {
                    $response['error'] = $e->getMessage();
                    $response['trace'] = $e->getTrace();
                }

                return response()->json($response, 500);
            }
        });
    }
} 