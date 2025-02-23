<?php

namespace App\Repositories\Abstracts;

use App\Models\Entities\Reservation;
use Illuminate\Support\Collection;

interface IReservationRepository
{
    public function create(array $data): Reservation;
    public function createItems(int $reservationId, array $items): void; // bir rezervasyon oluşturulduğunda seçilen koltuklar için ReservationItem kayıtlarını oluşturmak için 
    public function getAll(int $userId): Collection;
    public function findById(int $id): ?Reservation;
    public function confirm(int $id): bool;
    public function cancel(int $id): bool;
    public function getExpiredReservations(): Collection;
} 