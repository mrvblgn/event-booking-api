<?php

namespace App\Repositories\Interfaces;

use App\Models\Entities\Event;

interface IEventRepository
{
    public function create(array $data): Event;
    public function find(int $id): ?Event;
    public function update(Event $event, array $data): Event;
    public function delete(int $id): bool;
    public function all(): array;
} 