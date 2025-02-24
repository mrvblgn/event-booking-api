<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateReservationRequest;
use App\Models\DTOs\Reservations\Requests\CreateReservationRequestDto;
use App\Services\Abstracts\IReservationService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class ReservationController extends Controller
{
    use ApiResponse;

    public function __construct(
        private readonly IReservationService $reservationService
    ) {}

    public function store(CreateReservationRequest $request): JsonResponse
    {
        $dto = CreateReservationRequestDto::fromRequest($request->validated());
        $reservation = $this->reservationService->createReservation($dto);
        return $this->success($reservation, 'Reservation created successfully', 201);
    }

    public function index(): JsonResponse
    {
        $reservations = $this->reservationService->getReservations();
        return $this->success($reservations, 'Reservations retrieved successfully');
    }

    public function show(int $id): JsonResponse
    {
        $reservation = $this->reservationService->getReservation($id);
        return $this->success($reservation, 'Reservation retrieved successfully');
    }

    public function confirm(int $id): JsonResponse
    {
        $this->reservationService->confirmReservation($id);
        return $this->success(null, 'Reservation confirmed successfully');
    }

    public function destroy(int $id): JsonResponse
    {
        $this->reservationService->cancelReservation($id);
        return $this->success(null, 'Reservation cancelled successfully');
    }
} 