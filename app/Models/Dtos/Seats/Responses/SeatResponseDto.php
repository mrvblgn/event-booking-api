<?php

namespace App\Models\Dtos\Seats\Responses;

use App\Models\Entities\Seat;

class SeatResponseDto
{
    public function __construct(
        private readonly int $id,
        private readonly string $section,
        private readonly string $row,
        private readonly string $number,
        private readonly float $price,
        private readonly string $status
    ) {}

    public static function fromEntity(Seat $seat): self
    {
        return new self(
            id: $seat->id,
            section: $seat->section,
            row: $seat->row,
            number: $seat->number,
            price: $seat->price,
            status: $seat->status
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'section' => $this->section,
            'row' => $this->row,
            'number' => $this->number,
            'price' => $this->price,
            'status' => $this->status
        ];
    }
}
