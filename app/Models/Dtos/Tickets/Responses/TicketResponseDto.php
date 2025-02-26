<?php

namespace App\Models\Dtos\Tickets\Responses;

use App\Models\Entities\Ticket;

class TicketResponseDto
{
    public function __construct(
        public readonly string $ticket_code,
        public readonly string $status,
        public readonly array $seat,
        public readonly array $event,
        public readonly array $user
    ) {}

    public static function fromEntity(Ticket $ticket): self
    {
        return new self(
            ticket_code: $ticket->ticket_code,
            status: $ticket->status,
            seat: [
                'row' => $ticket->seat->row,
                'number' => $ticket->seat->number,
                'section' => $ticket->seat->section,
                'price' => $ticket->seat->price
            ],
            event: [
                'name' => $ticket->reservation->event->name,
                'start_date' => $ticket->reservation->event->start_date->format('Y-m-d H:i:s'),
                'end_date' => $ticket->reservation->event->end_date->format('Y-m-d H:i:s'),
                'venue' => $ticket->reservation->event->venue->name
            ],
            user: [
                'name' => $ticket->reservation->user->name,
                'email' => $ticket->reservation->user->email
            ]
        );
    }

    public function toArray(): array
    {
        return [
            'ticket_code' => $this->ticket_code,
            'status' => $this->status,
            'seat' => $this->seat,
            'event' => $this->event,
            'user' => $this->user
        ];
    }
}
