<?php

namespace App\Models\DTOs\Events\Responses;

use App\Models\Entities\Event;

class EventResponseDto
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $description,
        public readonly int $venue_id,
        public readonly string $venue_name,
        public readonly string $start_date,
        public readonly string $end_date,
        public readonly string $status,
        public readonly string $created_at,
        public readonly string $updated_at
    ) {}

    public static function fromEntity(Event $event): self
    {
        return new self(
            id: $event->id,
            name: $event->name,
            description: $event->description,
            venue_id: $event->venue_id,
            venue_name: $event->venue->name,
            start_date: $event->start_date->format('Y-m-d H:i:s'),
            end_date: $event->end_date->format('Y-m-d H:i:s'),
            status: $event->status,
            created_at: $event->created_at->format('Y-m-d H:i:s'),
            updated_at: $event->updated_at->format('Y-m-d H:i:s')
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'venue' => [
                'id' => $this->venue_id,
                'name' => $this->venue_name
            ],
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
