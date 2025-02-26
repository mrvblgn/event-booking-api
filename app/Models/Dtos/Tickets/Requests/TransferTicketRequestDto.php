<?php

namespace App\Models\Dtos\Tickets\Requests;

class TransferTicketRequestDto
{
    /**
     * @param string $code Bilet kodu
     * @param string $email Transfer edilecek kullanıcının email adresi
     */
    private function __construct(
        private readonly string $code,
        private readonly string $email
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            code: $data['ticket_code'],
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
