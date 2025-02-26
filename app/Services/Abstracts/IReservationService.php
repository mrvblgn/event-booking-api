<?php

namespace App\Services\Abstracts;

use App\Models\Dtos\Reservations\Requests\CreateReservationRequestDto;
use App\Models\Dtos\Reservations\Responses\ReservationResponseDto;
use Illuminate\Support\Collection;

interface IReservationService
{
    public function createReservation(CreateReservationRequestDto $dto): ReservationResponseDto;
    public function getReservations(): Collection;
    public function getReservation(int $id): ReservationResponseDto;
    public function confirmReservation(int $id): bool;
    public function cancelReservation(int $id): bool;
} 