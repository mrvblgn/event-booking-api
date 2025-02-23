<?php

namespace App\Http\Controllers;

use App\Models\DTOs\Events\Requests\CreateEventRequestDto;
use App\Models\DTOs\Events\Requests\UpdateEventRequestDto;
use App\Services\Abstracts\IEventService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class EventController extends Controller
{
    public function __construct(
        private readonly IEventService $eventService
    ) {}

    public function index(): JsonResponse
    {
        $events = $this->eventService->getAllEvents();
        return response()->json($events);
    }

    public function show(int $id): JsonResponse
    {
        $event = $this->eventService->getEventById($id);
        return response()->json($event);
    }

    public function store(Request $request): JsonResponse
    {
        // Request validation yapılacak
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'venue_id' => 'required|exists:venues,id',
            'start_date' => 'required|date|after:now',
            'end_date' => 'required|date|after:start_date',
            'status' => 'sometimes|string|in:draft,published,cancelled'
        ]);

        $dto = CreateEventRequestDto::fromRequest($request->all());
        $event = $this->eventService->createEvent($dto);

        return response()->json([
            'message' => 'Event created successfully',
            'data' => $event->toArray()
        ], 201);
    }

    public function update(int $id, Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'venue_id' => 'sometimes|exists:venues,id',
            'start_date' => 'sometimes|date|after:now',
            'end_date' => 'sometimes|date|after:start_date',
            'status' => 'sometimes|string|in:draft,published,cancelled'
        ]);

        $dto = UpdateEventRequestDto::fromRequest($request->all());
        $event = $this->eventService->updateEvent($id, $dto);

        return response()->json($event);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->eventService->deleteEvent($id);
        return response()->json(null, 204);
    }
} 