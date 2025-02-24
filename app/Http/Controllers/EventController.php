<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Services\Abstracts\IEventService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class EventController extends Controller
{
    use ApiResponse;

    public function __construct(
        private readonly IEventService $eventService
    ) {}

    public function index(): JsonResponse
    {
        $events = $this->eventService->getEvents();
        return $this->success($events, 'Events retrieved successfully');
    }

    public function show(int $id): JsonResponse
    {
        $event = $this->eventService->getEvent($id);
        return $this->success($event, 'Event retrieved successfully');
    }

    public function store(CreateEventRequest $request): JsonResponse
    {
        $event = $this->eventService->createEvent($request->validated());
        return $this->success($event, 'Event created successfully', 201);
    }

    public function update(UpdateEventRequest $request, int $id): JsonResponse
    {
        $event = $this->eventService->updateEvent($id, $request->validated());
        return $this->success($event, 'Event updated successfully');
    }

    public function destroy(int $id): JsonResponse
    {
        $this->eventService->deleteEvent($id);
        return $this->success(null, 'Event deleted successfully');
    }
} 