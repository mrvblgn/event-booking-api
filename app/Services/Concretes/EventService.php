<?php

namespace App\Services\Concretes;

use App\Models\Dtos\Events\Requests\CreateEventRequestDto;
use App\Models\Dtos\Events\Requests\UpdateEventRequestDto;
use App\Models\Dtos\Events\Responses\EventResponseDto;
use App\Repositories\Abstracts\IEventRepository;
use App\Services\Abstracts\IEventService;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Cache;
use App\Models\Dtos\Seats\Responses\SeatResponseDto;

class EventService implements IEventService
{
    public function __construct(
        private readonly IEventRepository $eventRepository
    ) {}

    public function getAllEvents(): Collection
    {
        return Cache::remember('events.all', 3600, function () {
            $events = $this->eventRepository->allWithVenue();
            return $events->map(function ($event) {
                return EventResponseDto::fromEntity($event);
            });
        });
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

    public function getEvents(): Collection
    {
        // N+1 problemini çözmek için venue ve seats ilişkilerini eager loading yapıyoruz
        return $this->eventRepository->getAllWith(['venue', 'seats']);
    }

    public function getEvent(int $id): Event
    {
        // Tek bir event için de ilişkileri eager loading yapıyoruz
        return $this->eventRepository->findWith($id, ['venue', 'seats']);
    }

    public function getEventSeats(int $eventId): Collection
    {
        $event = $this->eventRepository->findWithSeats($eventId);
        
        if (!$event) {
            throw new ModelNotFoundException('Event not found');
        }

        return $event->seats->map(function ($seat) {
            return SeatResponseDto::fromEntity($seat);
        });
    }
} 