<?php

namespace App\Models\Dtos\Events\Responses;

use App\Models\Entities\Event;

class EventResponseDto
{
    public function __construct(
        private readonly int $id,
        private readonly string $name,
        private readonly string $description,
        private readonly ?array $venue,
        private readonly ?string $start_date,
        private readonly ?string $end_date,
        private readonly string $status
    ) {}

    public static function fromEntity(Event $event): self
    {
        return new self(
            id: $event->id,
            name: $event->name,
            description: $event->description ?? '',
            venue: $event->venue ? [
                'id' => $event->venue->id,
                'name' => $event->venue->name,
                'address' => $event->venue->address
            ] : null,
            start_date: $event->start_date ? $event->start_date->format('Y-m-d H:i:s') : null,
            end_date: $event->end_date ? $event->end_date->format('Y-m-d H:i:s') : null,
            status: $event->status
        );
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getVenue(): ?array
    {
        return $this->venue;
    }

    public function getStartDate(): ?string
    {
        return $this->start_date;
    }

    public function getEndDate(): ?string
    {
        return $this->end_date;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'venue' => $this->venue,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'status' => $this->status
        ];
    }
}
