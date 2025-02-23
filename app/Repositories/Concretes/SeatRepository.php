<?php

namespace App\Repositories\Concretes;

use App\Models\Entities\Seat;
use App\Repositories\Abstracts\ISeatRepository;
use Illuminate\Support\Collection;

class SeatRepository implements ISeatRepository
{
    public function getEventSeats(int $eventId): Collection
    {
        return Seat::whereHas('tickets', function ($query) use ($eventId) {
            $query->where('event_id', $eventId);
        })->get();
    }

    public function getVenueSeats(int $venueId): Collection
    {
        return Seat::where('venue_id', $venueId)->get();
    }

    public function blockSeats(array $seatIds): bool
    {
        return Seat::whereIn('id', $seatIds)
            ->where('status', Seat::STATUS_AVAILABLE)
            ->update(['status' => Seat::STATUS_RESERVED]) > 0;
    }

    public function releaseSeats(array $seatIds): bool
    {
        return Seat::whereIn('id', $seatIds)
            ->where('status', Seat::STATUS_RESERVED)
            ->update(['status' => Seat::STATUS_AVAILABLE]) > 0;
    }
} 