<?php

namespace App\Http\Controllers;

use App\Models\DTOs\Events\Requests\CreateEventRequestDto;
use App\Services\EventService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class EventController extends Controller
{
    public function __construct(
        private readonly EventService $eventService
    ) {}

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
} 