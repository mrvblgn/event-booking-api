<?php

namespace App\Http\Controllers;

use App\Models\DTOs\Seats\Requests\BlockSeatsRequestDto;
use App\Services\Abstracts\ISeatService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SeatController extends Controller
{
    public function __construct(
        private readonly ISeatService $seatService
    ) {}

    public function getEventSeats(int $eventId): JsonResponse
    {
        $seats = $this->seatService->getEventSeats($eventId);
        return response()->json($seats);
    }

    public function getVenueSeats(int $venueId): JsonResponse
    {
        $seats = $this->seatService->getVenueSeats($venueId);
        return response()->json($seats);
    }

    public function blockSeats(Request $request): JsonResponse
    {
        $request->validate([
            'seat_ids' => 'required|array',
            'seat_ids.*' => 'required|integer|exists:seats,id'
        ]);

        try {
            $dto = BlockSeatsRequestDto::fromRequest($request->all());
            $success = $this->seatService->blockSeats($dto->getSeatIds());

            return response()->json([
                'message' => $success ? 'Seats blocked successfully' : 'Failed to block seats'
            ], $success ? 200 : 400);
        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function releaseSeats(Request $request): JsonResponse
    {
        $request->validate([
            'seat_ids' => 'required|array',
            'seat_ids.*' => 'required|integer|exists:seats,id'
        ]);

        $dto = BlockSeatsRequestDto::fromRequest($request->all());
        $success = $this->seatService->releaseSeats($dto->getSeatIds());

        return response()->json([
            'message' => $success ? 'Seats released successfully' : 'Failed to release seats'
        ], $success ? 200 : 400);
    }
} 