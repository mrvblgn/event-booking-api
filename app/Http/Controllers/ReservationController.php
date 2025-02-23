<?php

namespace App\Http\Controllers;

use App\Models\DTOs\Reservations\Requests\CreateReservationRequestDto;
use App\Services\Abstracts\IReservationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function __construct(
        private readonly IReservationService $reservationService
    ) {}

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'event_id' => 'required|integer|exists:events,id',
            'seat_ids' => 'required|array',
            'seat_ids.*' => 'required|integer|exists:seats,id'
        ]);

        try {
            $dto = CreateReservationRequestDto::fromRequest($request->all());
            $reservation = $this->reservationService->createReservation($dto);
            
            return response()->json($reservation, 201);
        } catch (\InvalidArgumentException $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function index(): JsonResponse
    {
        $reservations = $this->reservationService->getReservations();
        return response()->json($reservations);
    }

    public function show(int $id): JsonResponse
    {
        try {
            $reservation = $this->reservationService->getReservation($id);
            return response()->json($reservation);
        } catch (\InvalidArgumentException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    public function confirm(int $id): JsonResponse
    {
        try {
            $success = $this->reservationService->confirmReservation($id);
            return response()->json([
                'message' => $success ? 'Reservation confirmed' : 'Failed to confirm reservation'
            ], $success ? 200 : 400);
        } catch (\InvalidArgumentException $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $success = $this->reservationService->cancelReservation($id);
            return response()->json([
                'message' => $success ? 'Reservation cancelled' : 'Failed to cancel reservation'
            ], $success ? 200 : 400);
        } catch (\InvalidArgumentException $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
} 