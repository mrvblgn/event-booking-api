<?php

namespace App\Services\Concretes;

use App\Models\Dtos\Seats\Requests\BlockSeatsRequestDto;
use App\Models\Dtos\Seats\Responses\SeatResponseDto;
use App\Repositories\Abstracts\ISeatRepository;
use App\Services\Abstracts\ISeatService;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\DB;

class SeatService implements ISeatService
{
    public function __construct(
        private readonly ISeatRepository $seatRepository
    ) {}

    public function getEventSeats(int $eventId): Collection
    {
        return $this->seatRepository->getEventSeats($eventId);
    }

    public function getVenueSeats(int $venueId): Collection
    {
        return $this->seatRepository->getVenueSeats($venueId);
    }

    public function blockSeats(array $seatIds, int $userId): Collection
    {
        $seats = $this->seatRepository->findMany($seatIds);
        
        foreach ($seats as $seat) {
            if (!$seat->isAvailable()) {
                throw new \Exception("Seat {$seat->row}-{$seat->number} is not available");
            }
        }

        return $this->seatRepository->blockSeats($seats, $userId);
    }

    public function releaseSeats(array $seatIds): bool
    {
        if (empty($seatIds)) {
            throw new \InvalidArgumentException('No seats selected');
        }

        return $this->seatRepository->releaseSeats($seatIds);
    }
} 