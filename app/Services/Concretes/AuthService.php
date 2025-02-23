<?php

namespace App\Services\Concretes;

use App\Models\DTOs\Auth\Requests\RegisterRequestDto;
use App\Models\DTOs\Auth\Responses\AuthResponseDto;
use App\Models\DTOs\Auth\Requests\LoginRequestDto;
use App\Repositories\Interfaces\IAuthRepository;
use App\Services\Interfaces\IAuthService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService implements IAuthService
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
        $dto = new LoginRequestDto($email, $password);
        $user = $this->authRepository->findByEmail($dto->email);

        if (!$user || !Hash::check($dto->password, $user->password)) {
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

    public function refresh(): AuthResponseDto
    {
        $token = auth()->refresh();
        $user = auth()->user();

        return AuthResponseDto::fromUserAndToken($user, $token);
    }
} 