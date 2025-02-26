<?php

namespace App\Services\Abstracts;

use App\Models\Dtos\Auth\Requests\RegisterRequestDto;
use App\Models\Dtos\Auth\Responses\AuthResponseDto;

interface IAuthService
{
    public function register(array $data): array;
    public function login(array $credentials): string;
    public function logout(): void;
    public function refresh(): string;
}