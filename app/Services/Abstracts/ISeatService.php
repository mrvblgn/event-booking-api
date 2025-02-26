<?php

namespace App\Services\Abstracts;

use App\Models\Dtos\Seats\Responses\SeatResponseDto;
use Illuminate\Support\Collection;

interface ISeatService
{
    public function getEventSeats(int $eventId): Collection;
    public function getVenueSeats(int $venueId): Collection;
    public function blockSeats(array $seatIds, int $userId): Collection;
    public function releaseSeats(array $seatIds): bool;
} 