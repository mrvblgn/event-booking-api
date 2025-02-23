<?php

namespace App\Services\Interfaces;

use App\Models\DTOs\Auth\Requests\RegisterRequestDto;
use App\Models\DTOs\Auth\Responses\AuthResponseDto;

interface IAuthService
{
    public function register(RegisterRequestDto $dto): AuthResponseDto;
    public function login(string $email, string $password): AuthResponseDto;
    public function logout(): void;
    public function refresh(): AuthResponseDto;
} 