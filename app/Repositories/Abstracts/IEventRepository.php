<?php

namespace App\Repositories\Abstracts;

use App\Models\Entities\Event;
use Illuminate\Support\Collection;

interface IEventRepository
{
    public function create(array $data): Event;
    public function update(Event $event, array $data): Event;
    public function delete(int $id): bool;
    public function find(int $id): ?Event;
    public function findWithVenue(int $id): ?Event;
    public function all(): Collection;
    public function allWithVenue(): Collection;
    public function findByVenue(int $venueId): Collection;
} 