<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Models\Dtos\Events\Requests\CreateEventRequestDto;
use App\Services\Abstracts\IEventService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class EventController extends Controller
{
    use ApiResponse;

    public function __construct(
        private readonly IEventService $eventService
    ) {}

    public function index(): JsonResponse
    {
        $events = $this->eventService->getAllEvents();
        return $this->success([
            'status' => 'success',
            'message' => 'Events retrieved successfully',
            'data' => $events->map(fn($event) => $event->toArray())
        ]);
    }

    public function show(int $id): JsonResponse
    {
        try {
            $event = $this->eventService->getEventById($id);
            return $this->success([
                'status' => 'success',
                'message' => 'Event retrieved successfully',
                'data' => $event->toArray()
            ]);
        } catch (ModelNotFoundException $e) {
            return $this->error('Event not found', 404);
        }
    }

    public function store(CreateEventRequest $request): JsonResponse
    {
        try {
            $dto = CreateEventRequestDto::fromRequest($request->validated());
            $event = $this->eventService->createEvent($dto);
            
            return $this->success([
                'status' => 'success',
                'message' => 'Event created successfully',
                'data' => $event->toArray()
            ], 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
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

    public function getEventSeats(int $id): JsonResponse
    {
        try {
            $seats = $this->eventService->getEventSeats($id);
            return $this->success([
                'status' => 'success',
                'message' => 'Event seats retrieved successfully',
                'data' => $seats->map(fn($seat) => $seat->toArray())
            ]);
        } catch (ModelNotFoundException $e) {
            return $this->error('Event not found', 404);
        }
    }
}