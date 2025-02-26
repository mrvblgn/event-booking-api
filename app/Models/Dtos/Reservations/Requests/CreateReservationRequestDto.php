<?php

namespace App\Models\Dtos\Reservations\Requests;

class CreateReservationRequestDto
{
    /**
     * @param int $event_id
     * @param int[] $seat_ids
     */
    public function __construct(
        public readonly int $event_id,
        public readonly array $seat_ids
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            event_id: $data['event_id'],
            seat_ids: $data['seat_ids']
        );
    }

    public function toArray(): array
    {
        return [
            'event_id' => $this->event_id,
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

    public function getEventId(): int
    {
        return $this->event_id;
    }
}