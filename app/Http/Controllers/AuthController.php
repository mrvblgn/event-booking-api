<?php

namespace App\Http\Controllers;

use App\Models\DTOs\Auth\Requests\RegisterRequestDto;
use App\Services\Abstracts\IAuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(
        private readonly IAuthService $authService
    ) {}

    public function register(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required'
        ]);

        $dto = RegisterRequestDto::fromRequest($request->all());
        $response = $this->authService->register($dto);

        return response()->json($response->toArray(), 201);
    }

    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $response = $this->authService->login(
            $request->email,
            $request->password
        );

        return response()->json($response->toArray());
    }

    public function logout(): JsonResponse
    {
        $this->authService->logout();

        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    public function refresh(): JsonResponse
    {
        $response = $this->authService->refresh();

        return response()->json($response->toArray());
    }
} 