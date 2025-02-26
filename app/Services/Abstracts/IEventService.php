<?php

namespace App\Services\Abstracts;

use App\Models\Dtos\Events\Requests\CreateEventRequestDto;
use App\Models\Dtos\Events\Requests\UpdateEventRequestDto;
use App\Models\Dtos\Events\Responses\EventResponseDto;
use Illuminate\Support\Collection;

interface IEventService
{
    public function getAllEvents(): Collection;
    public function getEventById(int $id): EventResponseDto;
    public function createEvent(CreateEventRequestDto $dto): EventResponseDto;
    public function updateEvent(int $id, UpdateEventRequestDto $dto): EventResponseDto;
    public function deleteEvent(int $id): bool;
}