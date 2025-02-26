<?php

namespace App\Repositories\Concretes;

use App\Models\Entities\User;
use App\Repositories\Abstracts\IAuthRepository;
use Illuminate\Support\Facades\Hash;

class AuthRepository implements IAuthRepository
{
    public function register(array $data): User
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password'])
        ]);
    }

    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    public function createToken(User $user): string
    {
        return auth()->login($user);
    }

    public function revokeToken(User $user): void
    {
        auth()->logout();
    }
} 