<?php

namespace App\Http\Controllers;

use App\Http\Requests\BlockSeatsRequest;
use App\Http\Requests\ReleaseSeatsRequest;
use App\Services\Abstracts\ISeatService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class SeatController extends Controller
{
    use ApiResponse;

    public function __construct(
        private readonly ISeatService $seatService
    ) {}

    public function getEventSeats(int $eventId): JsonResponse
    {
        $seats = $this->seatService->getEventSeats($eventId);
        return $this->success($seats, 'Event seats retrieved successfully');
    }

    public function getVenueSeats(int $venueId): JsonResponse
    {
        $seats = $this->seatService->getVenueSeats($venueId);
        return $this->success($seats, 'Venue seats retrieved successfully');
    }

    public function blockSeats(BlockSeatsRequest $request): JsonResponse
    {
        $this->seatService->blockSeats($request->validated()['seat_ids']);
        return $this->success(null, 'Seats blocked successfully');
    }

    public function releaseSeats(ReleaseSeatsRequest $request): JsonResponse
    {
        $this->seatService->releaseSeats($request->validated()['seat_ids']);
        return $this->success(null, 'Seats released successfully');
    }
} 