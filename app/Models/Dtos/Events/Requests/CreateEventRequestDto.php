<?php

namespace App\Models\DTOs\Events\Requests;

class CreateEventRequestDto
{
    public function __construct(
        public readonly string $name,
        public readonly string $description,
        public readonly int $venue_id,
        public readonly string $start_date,
        public readonly string $end_date,
        public readonly string $status = 'draft'
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            name: $data['name'],
            description: $data['description'],
            venue_id: $data['venue_id'],
            start_date: $data['start_date'],
            end_date: $data['end_date'],
            status: $data['status'] ?? 'draft'
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'venue_id' => $this->venue_id,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'status' => $this->status
        ];
    }
}
