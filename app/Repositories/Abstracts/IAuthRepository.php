<?php

namespace App\Repositories\Abstracts;

use App\Models\Entities\User;

interface IAuthRepository
{
    public function register(array $data): User;
    public function findByEmail(string $email): ?User;
    public function createToken(User $user): string;
    public function revokeToken(User $user): void;
} 