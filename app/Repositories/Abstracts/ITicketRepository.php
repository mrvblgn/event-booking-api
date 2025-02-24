<?php

namespace App\Repositories\Abstracts;

use App\Models\Entities\Ticket;
use Illuminate\Support\Collection;

interface ITicketRepository
{
    public function findByCode(string $ticketCode): ?Ticket;
    public function getUserTickets(int $userId): Collection;
    public function transfer(string $ticketCode, int $newUserId): bool;
    public function cancel(string $ticketCode): bool;
    public function createFromReservation(int $reservationId, array $seatIds): void;
} 