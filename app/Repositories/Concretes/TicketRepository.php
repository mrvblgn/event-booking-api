<?php

namespace App\Repositories\Concretes;

use App\Models\Entities\Ticket;
use App\Repositories\Abstracts\ITicketRepository;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TicketRepository implements ITicketRepository
{
    public function findByCode(string $code): ?Ticket
    {
        return Ticket::where('ticket_code', $code)
            ->with(['reservation', 'event', 'seat'])
            ->firstOrFail();
    }

    public function getUserTickets(int $userId): Collection
    {
        return Ticket::whereHas('reservation', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->get();
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

    public function transferTicket(Ticket $ticket, string $email): bool
    {
        $newUser = User::where('email', $email)->firstOrFail();
        
        return $ticket->reservation->update([
            'user_id' => $newUser->id
        ]);
    }
} 