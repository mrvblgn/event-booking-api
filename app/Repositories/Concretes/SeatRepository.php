<?php

namespace App\Repositories\Concretes;

use App\Models\Entities\Seat;
use App\Repositories\Abstracts\ISeatRepository;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class SeatRepository implements ISeatRepository
{
    public function getEventSeats(int $eventId): Collection
    {
        $seats = Seat::where('event_id', $eventId)
            ->with(['venue'])  // İlişkili verileri de alalım
            ->get()
            ->map(function ($seat) {
                return [
                    'id' => $seat->id,
                    'section' => $seat->section,
                    'row' => $seat->row,
                    'number' => $seat->number,
                    'price' => $seat->price,
                    'status' => $seat->status,
                    'venue' => $seat->venue ? [
                        'id' => $seat->venue->id,
                        'name' => $seat->venue->name
                    ] : null
                ];
            });

        if ($seats->isEmpty()) {
            throw new ModelNotFoundException('No seats found for this event');
        }

        return $seats;
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