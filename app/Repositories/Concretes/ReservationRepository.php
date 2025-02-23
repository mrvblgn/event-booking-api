<?php

namespace App\Repositories\Concretes;

use App\Models\Entities\Reservation;
use App\Models\Entities\ReservationItem;
use App\Repositories\Abstracts\IReservationRepository;
use Illuminate\Support\Collection;

class ReservationRepository implements IReservationRepository
{
    public function create(array $data): Reservation
    {
        return Reservation::create($data);
    }

    public function createItems(int $reservationId, array $items): void
    {
        foreach ($items as $item) {
            ReservationItem::create([
                'reservation_id' => $reservationId,
                'seat_id' => $item['seat_id'],
                'price' => $item['price']
            ]);
        }
    }

    public function getAll(int $userId): Collection
    {
        return Reservation::with(['event.venue', 'reservationItems.seat'])
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function findById(int $id): ?Reservation
    {
        return Reservation::with(['event.venue', 'reservationItems.seat'])
            ->find($id);
    }

    public function confirm(int $id): bool
    {
        return $this->updateStatus($id, Reservation::STATUS_CONFIRMED);
    }

    public function cancel(int $id): bool
    {
        return $this->updateStatus($id, Reservation::STATUS_CANCELLED);
    }

    public function getExpiredReservations(): Collection
    {
        return Reservation::where('status', Reservation::STATUS_PENDING)
            ->where('expires_at', '<', now())
            ->get();
    }

    private function updateStatus(int $id, string $status): bool
    {
        return Reservation::where('id', $id)
            ->update(['status' => $status]) > 0;
    }
} 