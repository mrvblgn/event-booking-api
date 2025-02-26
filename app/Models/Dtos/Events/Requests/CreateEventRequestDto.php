<?php

namespace App\Models\Dtos\Events\Requests;

class CreateEventRequestDto
{
    public function __construct(
        private readonly string $title,
        private readonly string $description,
        private readonly int $venue_id,
        private readonly string $start_time,
        private readonly string $end_time,
        private readonly ?string $status = 'draft'
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            title: $data['title'],
            description: $data['description'],
            venue_id: $data['venue_id'],
            start_time: $data['start_time'],
            end_time: $data['end_time'],
            status: $data['status'] ?? 'draft'
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->title,
            'description' => $this->description,
            'venue_id' => $this->venue_id,
            'start_date' => $this->start_time,
            'end_date' => $this->end_time,
            'status' => $this->status
        ];
    }
}
