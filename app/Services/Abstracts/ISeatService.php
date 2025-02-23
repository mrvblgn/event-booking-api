<?php

namespace App\Services\Abstracts;

use App\Models\DTOs\Seats\Responses\SeatResponseDto;
use Illuminate\Support\Collection;

interface ISeatService
{
    public function getEventSeats(int $eventId): Collection;
    public function getVenueSeats(int $venueId): Collection;
    public function blockSeats(array $seatIds): bool;
    public function releaseSeats(array $seatIds): bool;
} 