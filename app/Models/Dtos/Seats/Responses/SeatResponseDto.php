<?php

namespace App\Models\DTOs\Seats\Responses;

use App\Models\Entities\Seat;

class SeatResponseDto
{
    public function __construct(
        public readonly string $row,
        public readonly int $number,
        public readonly string $section,
        public readonly float $price,
        public readonly string $status
    ) {}

    public static function fromEntity(Seat $seat): self
    {
        return new self(
            row: $seat->row,
            number: $seat->number,
            section: $seat->section,
            price: $seat->price,
            status: $seat->status
        );
    }

    public function toArray(): array
    {
        return [
            'row' => $this->row,
            'number' => $this->number,
            'section' => $this->section,
            'price' => $this->price,
            'status' => $this->status
        ];
    }
}
