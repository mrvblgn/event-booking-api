<?php

namespace App\Services\Concretes;

use App\Models\DTOs\Reservations\Requests\CreateReservationRequestDto;
use App\Models\DTOs\Reservations\Responses\ReservationResponseDto;
use App\Models\Entities\Reservation;
use App\Repositories\Abstracts\IReservationRepository;
use App\Repositories\Abstracts\ISeatRepository;
use App\Services\Abstracts\IReservationService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReservationService implements IReservationService
{
    public function __construct(
        private readonly IReservationRepository $reservationRepository,
        private readonly ISeatRepository $seatRepository
    ) {}

    public function createReservation(CreateReservationRequestDto $dto): ReservationResponseDto
    {
        try {
            DB::beginTransaction();

            // Koltukları blokla
            $success = $this->seatRepository->blockSeats($dto->getSeatIds());
            if (!$success) {
                throw new \InvalidArgumentException('Seats are not available');
            }

            // Rezervasyon oluştur
            $reservation = $this->reservationRepository->create([
                'user_id' => Auth::id(),
                'event_id' => $dto->getEventId(),
                'status' => Reservation::STATUS_PENDING,
                'expires_at' => now()->addMinutes(15)
            ]);

            // Rezervasyon detaylarını oluştur
            $items = [];
            foreach ($dto->getSeatIds() as $seatId) {
                $seat = $this->seatRepository->findById($seatId);
                $items[] = [
                    'seat_id' => $seatId,
                    'price' => $seat->price
                ];
            }
            
            $this->reservationRepository->createItems($reservation->id, $items);

            DB::commit();
            return ReservationResponseDto::fromEntity($reservation);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function getReservations(): Collection
    {
        $reservations = $this->reservationRepository->getAll(Auth::id());
        return $reservations->map(fn($reservation) => ReservationResponseDto::fromEntity($reservation));
    }

    public function getReservation(int $id): ReservationResponseDto
    {
        $reservation = $this->reservationRepository->findById($id);
        if (!$reservation || $reservation->user_id !== Auth::id()) {
            throw new \InvalidArgumentException('Reservation not found');
        }

        return ReservationResponseDto::fromEntity($reservation);
    }

    public function confirmReservation(int $id): bool
    {
        $reservation = $this->reservationRepository->findById($id);
        if (!$reservation || $reservation->user_id !== Auth::id()) {
            throw new \InvalidArgumentException('Reservation not found');
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