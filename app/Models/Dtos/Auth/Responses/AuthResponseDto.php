<?php

namespace App\Models\DTOs\Auth\Responses;

class AuthResponseDto
{
    public function __construct(
        public readonly string $token,
        public readonly string $name,
        public readonly string $email
    ) {}

    public static function fromUserAndToken($user, string $token): self
    {
        return new self(
            token: $token,
            name: $user->name,
            email: $user->email
        );
    }

    public function toArray(): array
    {
        return [
            'token' => $this->token,
            'user' => [
                'name' => $this->name,
                'email' => $this->email
            ]
        ];
    }
} 