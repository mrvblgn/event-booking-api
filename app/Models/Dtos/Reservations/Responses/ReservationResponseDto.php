<?php

namespace App\Models\DTOs\Reservations\Responses;

use App\Models\Entities\Reservation;

class ReservationResponseDto
{
    public function __construct(
        public readonly string $status,
        public readonly float $total_amount,
        public readonly string $expires_at,
        public readonly array $seats,
        public readonly array $event
    ) {}

    public static function fromEntity(Reservation $reservation): self
    {
        return new self(
            status: $reservation->status,
            total_amount: $reservation->total_amount,
            expires_at: $reservation->expires_at->format('Y-m-d H:i:s'),
            seats: $reservation->reservationItems->map(fn($item) => [
                'row' => $item->seat->row,
                'number' => $item->seat->number,
                'section' => $item->section,
                'price' => $item->price
            ])->toArray(),
            event: [
                'name' => $reservation->event->name,
                'start_date' => $reservation->event->start_date->format('Y-m-d H:i:s'),
                'end_date' => $reservation->event->end_date->format('Y-m-d H:i:s'),
                'venue' => $reservation->event->venue->name
            ]
        );
    }

    public function toArray(): array
    {
        return [
            'status' => $this->status,
            'total_amount' => $this->total_amount,
            'expires_at' => $this->expires_at,
            'seats' => $this->seats,
            'event' => $this->event
        ];
    }
}
