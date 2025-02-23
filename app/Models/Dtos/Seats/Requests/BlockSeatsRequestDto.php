<?php

namespace App\Models\DTOs\Seats\Requests;

class BlockSeatsRequestDto
{
    /**
     * @param int[] $seat_ids
     */
    public function __construct(
        public readonly array $seat_ids
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            seat_ids: $data['seat_ids']
        );
    }

    public function toArray(): array
    {
        return [
            'seat_ids' => $this->seat_ids
        ];
    }

    /**
     * @return int[]
     */
    public function getSeatIds(): array
    {
        return $this->seat_ids;
    }
} 