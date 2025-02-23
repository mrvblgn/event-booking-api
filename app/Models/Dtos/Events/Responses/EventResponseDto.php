<?php

namespace App\Models\DTOs\Events\Responses;

use App\Models\Entities\Event;

class EventResponseDto
{
    public function __construct(
        public readonly string $name,
        public readonly string $description,
        public readonly array $venue,
        public readonly string $start_date,
        public readonly string $end_date,
        public readonly string $status
    ) {}

    public static function fromEntity(Event $event): self
    {
        return new self(
            name: $event->name,
            description: $event->description,
            venue: [
                'name' => $event->venue->name,
                'address' => $event->venue->address
            ],
            start_date: $event->start_date->format('Y-m-d H:i:s'),
            end_date: $event->end_date->format('Y-m-d H:i:s'),
            status: $event->status
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'venue' => $this->venue,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'status' => $this->status
        ];
    }
}
