<?php

namespace App\Repositories\Concretes;

use App\Models\Entities\Event;
use App\Repositories\Abstracts\IEventRepository;
use Illuminate\Support\Collection;

class EventRepository implements IEventRepository
{
    public function create(array $data): Event
    {
        return Event::create($data);
    }

    public function update(Event $event, array $data): Event
    {
        $event->update($data);
        return $event;
    }

    public function delete(int $id): bool
    {
        return Event::destroy($id) > 0;
    }

    public function find(int $id): ?Event
    {
        return Event::find($id);
    }

    public function findWithVenue(int $id): ?Event
    {
        return Event::with('venue')->find($id);
    }

    public function all(): Collection
    {
        return Event::all();
    }

    public function allWithVenue(): Collection
    {
        return Event::with('venue')->get();
    }

    public function findByVenue(int $venueId): Collection
    {
        return Event::where('venue_id', $venueId)->get();
    }
} 