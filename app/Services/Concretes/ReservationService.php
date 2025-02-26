<?php

namespace App\Services\Concretes;

use App\Models\Dtos\Reservations\Requests\CreateReservationRequestDto;
use App\Models\Dtos\Reservations\Responses\ReservationResponseDto;
use App\Models\Entities\Reservation;
use App\Repositories\Abstracts\IReservationRepository;
use App\Repositories\Abstracts\ISeatRepository;
use App\Services\Abstracts\IReservationService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Auth\Access\AuthorizationException;
use App\Repositories\Abstracts\IEventRepository;
use Illuminate\Support\Facades\Cache;

class ReservationService implements IReservationService
{
    public function __construct(
        private readonly IReservationRepository $reservationRepository,
        private readonly ISeatRepository $seatRepository,
        private readonly IEventRepository $eventRepository
    ) {}

    public function createReservation(CreateReservationRequestDto $dto): ReservationResponseDto
    {
        return DB::transaction(function () use ($dto) {
            // Koltukları kilitle
            $seats = $this->seatRepository->lockForUpdate()->findMany($dto->getSeatIds());
            
            // Müsaitlik kontrolü
            if ($seats->where('status', '!=', 'available')->isNotEmpty()) {
                throw new \InvalidArgumentException('Some seats are not available');
            }

            // Rezervasyon oluştur
            $reservation = $this->reservationRepository->create([
                'user_id' => auth()->id(),
                'event_id' => $dto->getEventId(),
                'status' => Reservation::STATUS_PENDING
            ]);

            // Koltukları güncelle
            $this->seatRepository->updateMany($dto->getSeatIds(), ['status' => 'reserved']);

            // Rezervasyon itemları oluştur
            foreach ($dto->getSeatIds() as $seatId) {
                $this->reservationRepository->createItem([
                    'reservation_id' => $reservation->id,
                    'seat_id' => $seatId
                ]);
            }

            // Cache'i temizle
            Cache::tags(['events', 'reservations'])->flush();
            
            return ReservationResponseDto::fromEntity($reservation);
        });
    }

    public function getReservations(): Collection
    {
        $userId = Auth::id();
        return Cache::remember("reservations.user.{$userId}", 1800, function () {
            return $this->reservationRepository->getAllWith([
                'event', 
                'event.venue',
                'reservationItems',
                'reservationItems.seat'
            ]);
        });
    }

    public function getReservation(int $id): ReservationResponseDto
    {
        $reservation = $this->reservationRepository->findWith($id, [
            'event',
            'event.venue',
            'reservationItems',
            'reservationItems.seat'
        ]);

        if (!$reservation || $reservation->user_id !== Auth::id()) {
            throw new ModelNotFoundException('Reservation not found');
        }

        return ReservationResponseDto::fromEntity($reservation);
    }

    public function confirmReservation(int $id): bool
    {
        $reservation = $this->reservationRepository->findById($id);
        
        if (!$reservation) {
            throw new ModelNotFoundException('Reservation not found');
        }

        if ($reservation->user_id !== Auth::id()) {
            throw new AuthorizationException('You are not authorized to confirm this reservation');
        }

        if (!$reservation->isPending()) {
            throw new \InvalidArgumentException('Reservation cannot be confirmed');
        }

        if ($reservation->isExpired()) {
            throw new \InvalidArgumentException('Reservation has expired');
        }

        return $this->reservationRepository->confirm($id);
    }

    public function cancelReservation(int $id): bool
    {
        $reservation = $this->reservationRepository->findById($id);
        if (!$reservation || $reservation->user_id !== Auth::id()) {
            throw new \InvalidArgumentException('Reservation not found');
        }

        if (!$reservation->isPending()) {
            throw new \InvalidArgumentException('Reservation cannot be cancelled');
        }

        // Koltukları serbest bırak
        $seatIds = $reservation->reservationItems->pluck('seat_id')->toArray();
        $this->seatRepository->releaseSeats($seatIds);

        return $this->reservationRepository->cancel($id);
    }
} 