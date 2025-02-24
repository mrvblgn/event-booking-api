<?php

namespace App\Repositories\Concretes;

use App\Models\Entities\Ticket;
use App\Repositories\Abstracts\ITicketRepository;
use Illuminate\Support\Collection;

class TicketRepository implements ITicketRepository
{
    public function findByCode(string $ticketCode): ?Ticket
    {
        return Ticket::with(['reservation.event.venue', 'reservation.user', 'seat'])
            ->where('ticket_code', $ticketCode)
            ->first();
    }

    public function getUserTickets(int $userId): Collection
    {
        return Ticket::with(['reservation.event.venue', 'seat'])
            ->whereHas('reservation', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->get();
    }

    public function transfer(string $ticketCode, int $newUserId): bool
    {
        $ticket = $this->findByCode($ticketCode);
        if (!$ticket || !$ticket->canBeTransferred()) {
            return false;
        }

        $ticket->reservation->user_id = $newUserId;
        $ticket->status = Ticket::STATUS_TRANSFERRED;
        
        return $ticket->save();
    }

    public function cancel(string $ticketCode): bool
    {
        $ticket = $this->findByCode($ticketCode);
        if (!$ticket || !$ticket->canBeCancelled()) {
            return false;
        }

        $ticket->status = Ticket::STATUS_CANCELLED;
        return $ticket->save();
    }

    public function createFromReservation(int $reservationId, array $seatIds): void
    {
        foreach ($seatIds as $seatId) {
            Ticket::create([
                'reservation_id' => $reservationId,
                'seat_id' => $seatId,
                'status' => Ticket::STATUS_ACTIVE
            ]);
        }
    }
} 