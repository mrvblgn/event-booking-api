<?php

namespace App\Repositories\Abstracts;

use App\Models\Entities\Seat;
use Illuminate\Support\Collection;

interface ISeatRepository
{
    public function getEventSeats(int $eventId): Collection;
    public function getVenueSeats(int $venueId): Collection;
    public function blockSeats(array $seatIds): bool;
    public function releaseSeats(array $seatIds): bool;
}