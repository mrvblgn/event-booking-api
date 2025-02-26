<?php

namespace App\Models\Dtos\Venues\Responses;

use App\Models\Entities\Venue;

class VenueResponseDto
{
    public function __construct(
        public readonly string $name,
        public readonly string $address,
        public readonly int $capacity
    ) {}

    public static function fromEntity(Venue $venue): self
    {
        return new self(
            name: $venue->name,
            address: $venue->address,
            capacity: $venue->capacity
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