<?php

namespace App\Models\Dtos\Events\Requests;

class UpdateEventRequestDto
{
    public function __construct(
        public readonly ?string $name = null,
        public readonly ?string $description = null,
        public readonly ?int $venue_id = null,
        public readonly ?string $start_date = null,
        public readonly ?string $end_date = null,
        public readonly ?string $status = null
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            name: $data['name'] ?? null,
            description: $data['description'] ?? null,
            venue_id: $data['venue_id'] ?? null,
            start_date: $data['start_date'] ?? null,
            end_date: $data['end_date'] ?? null,
            status: $data['status'] ?? null
        );
    }

    public function toArray(): array
    {
        return array_filter([ // array_filter kullanarak null olmayan değerleri döndürüyoruz
            'name' => $this->name,
            'description' => $this->description,
            'venue_id' => $this->venue_id,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'status' => $this->status
        ], fn($value) => !is_null($value));
    }
}
