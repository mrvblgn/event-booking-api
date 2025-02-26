<?php

namespace App\Http\Controllers;

use App\Http\Requests\BlockSeatsRequest;
use App\Http\Requests\ReleaseSeatsRequest;
use App\Services\Abstracts\ISeatService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class SeatController extends Controller
{
    use ApiResponse;

    public function __construct(
        private readonly ISeatService $seatService
    ) {}

    public function getEventSeats(int $id): JsonResponse
    {
        try {
            $seats = $this->seatService->getEventSeats($id);
            return $this->success([
                'status' => 'success',
                'message' => 'Event seats retrieved successfully',
                'data' => $seats
            ]);
        } catch (ModelNotFoundException $e) {
            return $this->error('Event not found', 404);
        }
    }

    public function getVenueSeats(int $id): JsonResponse
    {
        try {
            $seats = $this->seatService->getVenueSeats($id);
            return $this->success($seats, 'Venue seats retrieved successfully');
        } catch (ModelNotFoundException $e) {
            return $this->error('Venue not found', 404);
        }
    }

    public function blockSeats(BlockSeatsRequest $request): JsonResponse
    {
        try {
            $result = $this->seatService->blockSeats($request->validated()['seat_ids']);
            
            return $this->success([
                'status' => 'success',
                'message' => 'Seats blocked successfully',
                'data' => $result
            ]);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 400);
        }
    }

    public function releaseSeats(ReleaseSeatsRequest $request): JsonResponse
    {
        $this->seatService->releaseSeats($request->validated()['seat_ids']);
        return $this->success(null, 'Seats released successfully');
    }
} 