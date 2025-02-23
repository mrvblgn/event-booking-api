<?php

namespace App\Services;

use App\Models\DTOs\Events\Requests\CreateEventRequestDto;
use App\Models\DTOs\Events\Responses\EventResponseDto;
use App\Repositories\Interfaces\IEventRepository;

class EventService
{
    public function __construct(
        private readonly IEventRepository $eventRepository
    ) {}

    public function createEvent(CreateEventRequestDto $dto): EventResponseDto
    {
        $event = $this->eventRepository->create($dto->toArray());
        
        return EventResponseDto::fromEntity($event);
    }
} 