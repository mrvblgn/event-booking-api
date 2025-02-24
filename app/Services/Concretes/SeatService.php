<?php

namespace App\Services\Concretes;

use App\Models\DTOs\Seats\Requests\BlockSeatsRequestDto;
use App\Models\DTOs\Seats\Responses\SeatResponseDto;
use App\Repositories\Abstracts\ISeatRepository;
use App\Services\Abstracts\ISeatService;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Auth\Access\AuthorizationException;

class SeatService implements ISeatService
{
    public function __construct(
        private readonly ISeatRepository $seatRepository
    ) {}

    public function getEventSeats(int $eventId): Collection
    {
        $seats = $this->seatRepository->getEventSeats($eventId);
        
        if ($seats->isEmpty()) {
            throw new ModelNotFoundException('No seats found for this event');
        }

        return $seats;
    }

    public function getVenueSeats(int $venueId): Collection
    {
        $seats = $this->seatRepository->getVenueSeats($venueId);
        
        if ($seats->isEmpty()) {
            throw new ModelNotFoundException('No seats found for this venue');
        }

        return $seats;
    }

    public function blockSeats(array $seatIds): void
    {
        if (empty($seatIds)) {
            throw new \InvalidArgumentException('No seats selected');
        }

        $success = $this->seatRepository->blockSeats($seatIds);
        
        if (!$success) {
            throw new \InvalidArgumentException('Failed to block seats');
        }
    }

    public function releaseSeats(array $seatIds): void
    {
        if (empty($seatIds)) {
            throw new \InvalidArgumentException('No seats selected');
        }

        $success = $this->seatRepository->releaseSeats($seatIds);
        
        if (!$success) {
            throw new \InvalidArgumentException('Failed to release seats');
        }
    }
} 