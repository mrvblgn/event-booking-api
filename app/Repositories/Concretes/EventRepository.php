<?php

namespace App\Repositories\Concretes;

use App\Models\Entities\Event;
use App\Repositories\Interfaces\IEventRepository;

class EventRepository implements IEventRepository
{
    public function create(array $data): Event
    {
        return Event::create($data);
    }

    public function find(int $id): ?Event
    {
        return Event::find($id);
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

    public function all(): array
    {
        return Event::all()->toArray();
    }
} 