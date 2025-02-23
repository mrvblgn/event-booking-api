<?php

namespace App\Services\Abstracts;

use App\Models\DTOs\Reservations\Requests\CreateReservationRequestDto;
use App\Models\DTOs\Reservations\Responses\ReservationResponseDto;
use Illuminate\Support\Collection;

interface IReservationService
{
    public function createReservation(CreateReservationRequestDto $dto): ReservationResponseDto;
    public function getReservations(): Collection;
    public function getReservation(int $id): ReservationResponseDto;
    public function confirmReservation(int $id): bool;
    public function cancelReservation(int $id): bool;
} 