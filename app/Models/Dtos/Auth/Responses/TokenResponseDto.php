<?php

namespace App\Models\Dtos\Auth\Responses;

class TokenResponseDto
{
    public function __construct(
        public readonly string $access_token,
        public readonly string $token_type,
        public readonly int $expires_in
    ) {}

    public static function fromTokenData(array $tokenData): self
    {
        return new self(
            access_token: $tokenData['access_token'],
            token_type: $tokenData['token_type'],
            expires_in: $tokenData['expires_in']
        );
    }

    public function toArray(): array
    {
        return [
            'access_token' => $this->access_token,
            'token_type' => $this->token_type,
            'expires_in' => $this->expires_in
        ];
    }
}
