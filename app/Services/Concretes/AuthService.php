<?php

namespace App\Services\Concretes;

use App\Services\Abstracts\IAuthService;
use App\Models\Entities\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\AuthenticationException;

class AuthService implements IAuthService
{
    public function register(array $data): array
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password'])
        ]);

        $token = auth()->login($user);
        return ['user' => $user, 'token' => $token];
    }

    public function login(array $credentials): string
    {
        $token = auth()->attempt($credentials);
        
        if (!$token) {
            throw new AuthenticationException('Invalid credentials');
        }

        return $token;
    }

    public function refresh(): string
    {
        try {
            return auth()->refresh();
        } catch (\Exception $e) {
            throw new AuthenticationException('Unable to refresh token');
        }
    }

    public function logout(): void
    {
        try {
            auth()->logout();
        } catch (\Exception $e) {
            throw new AuthenticationException('Unable to logout');
        }
    }
} 