<?php

namespace App\Models\DTOs\Tickets\Requests;

class TransferTicketRequestDto
{
    /**
     * @param string $code Bilet kodu
     * @param string $email Transfer edilecek kullanıcının email adresi
     */
    public function __construct(
        public readonly string $code,
        public readonly string $email
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            code: $data['code'],
            email: $data['email']
        );
    }

    public function toArray(): array
    {
        return [
            'code' => $this->code,
            'email' => $this->email
        ];
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}
