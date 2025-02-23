<?php

namespace App\Services\Concretes;

use App\Models\DTOs\Events\Requests\CreateEventRequestDto;
use App\Models\DTOs\Events\Requests\UpdateEventRequestDto;
use App\Models\DTOs\Events\Responses\EventResponseDto;
use App\Repositories\Abstracts\IEventRepository;
use App\Services\Abstracts\IEventService;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class EventService implements IEventService
{
    public function __construct(
        private readonly IEventRepository $eventRepository
    ) {}

    public function getAllEvents(): Collection
    {
        $events = $this->eventRepository->allWithVenue();
        return $events->map(fn($event) => EventResponseDto::fromEntity($event));
    }

    public function getEventById(int $id): EventResponseDto
    {
        $event = $this->eventRepository->findWithVenue($id);
        
        if (!$event) {
            throw new ModelNotFoundException('Event not found');
        }

        return EventResponseDto::fromEntity($event);
    }

    public function createEvent(CreateEventRequestDto $dto): EventResponseDto
    {
        $event = $this->eventRepository->create($dto->toArray());
        return EventResponseDto::fromEntity($event);
    }

    public function updateEvent(int $id, UpdateEventRequestDto $dto): EventResponseDto
    {
        $event = $this->eventRepository->find($id);
        
        if (!$event) {
            throw new ModelNotFoundException('Event not found');
        }

        $event = $this->eventRepository->update($event, $dto->toArray());
        return EventResponseDto::fromEntity($event);
    }

    public function deleteEvent(int $id): bool
    {
        return $this->eventRepository->delete($id);
    }
} 