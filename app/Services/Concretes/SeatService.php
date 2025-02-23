<?php

namespace App\Services\Concretes;

use App\Models\DTOs\Seats\Requests\BlockSeatsRequestDto;
use App\Models\DTOs\Seats\Responses\SeatResponseDto;
use App\Repositories\Abstracts\ISeatRepository;
use App\Services\Abstracts\ISeatService;
use Illuminate\Support\Collection;

class SeatService implements ISeatService
{
    public function __construct(
        private readonly ISeatRepository $seatRepository
    ) {}

    public function getEventSeats(int $eventId): Collection
    {
        $seats = $this->seatRepository->getEventSeats($eventId);
        return $seats->map(fn($seat) => SeatResponseDto::fromEntity($seat));
    }

    public function getVenueSeats(int $venueId): Collection
    {
        $seats = $this->seatRepository->getVenueSeats($venueId);
        return $seats->map(fn($seat) => SeatResponseDto::fromEntity($seat));
    }

    public function blockSeats(array $seatIds): bool
    {
        // Önce koltukların müsait olup olmadığını kontrol et
        $seats = $this->seatRepository->getVenueSeats($venueId);
        $availableSeats = $seats->filter(fn($seat) => $seat->isAvailable());
        $availableSeatIds = $availableSeats->pluck('id')->toArray();

        // Bloklanmak istenen tüm koltuklar müsait mi?
        $unavailableSeats = array_diff($seatIds, $availableSeatIds);
        if (!empty($unavailableSeats)) {
            throw new \InvalidArgumentException('Some seats are not available');
        }

        return $this->seatRepository->blockSeats($seatIds);
    }

    public function releaseSeats(array $seatIds): bool
    {
        return $this->seatRepository->releaseSeats($seatIds);
    }
} 