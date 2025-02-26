<?php

namespace App\Models\Dtos\Venues\Requests;

class CreateVenueRequestDto
{
    public function __construct(
        public readonly string $name,
        public readonly string $address,
        public readonly int $capacity
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            name: $data['name'],
            address: $data['address'],
            capacity: $data['capacity']
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'address' => $this->address,
            'capacity' => $this->capacity
        ];
    }
} 