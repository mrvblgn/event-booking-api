<?php

namespace App\Services;

use App\Models\DTOs\Auth\Requests\RegisterRequestDto;
use App\Models\DTOs\Auth\Responses\AuthResponseDto;
use App\Repositories\Interfaces\IAuthRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    public function __construct(
        private readonly IAuthRepository $authRepository
    ) {}

    public function register(RegisterRequestDto $dto): AuthResponseDto
    {
        $user = $this->authRepository->register($dto->toArray());
        $token = $this->authRepository->createToken($user);

        return AuthResponseDto::fromUserAndToken($user, $token);
    }

    public function login(string $email, string $password): AuthResponseDto
    {
        $user = $this->authRepository->findByEmail($email);

        if (!$user || !Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $this->authRepository->createToken($user);

        return AuthResponseDto::fromUserAndToken($user, $token);
    }

    public function logout(): void
    {
        $this->authRepository->revokeToken(auth()->user());
    }
} 