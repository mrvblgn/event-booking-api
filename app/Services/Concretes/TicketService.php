<?php

namespace App\Services\Concretes;

use App\Models\DTOs\Tickets\Requests\TransferTicketRequestDto;
use App\Models\DTOs\Tickets\Responses\TicketResponseDto;
use App\Repositories\Abstracts\ITicketRepository;
use App\Services\Abstracts\ITicketService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use PDF;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Auth\Access\AuthorizationException;

class TicketService implements ITicketService
{
    public function __construct(
        private readonly ITicketRepository $ticketRepository
    ) {}

    public function getUserTickets(): Collection
    {
        $tickets = $this->ticketRepository->getUserTickets(Auth::id());
        return $tickets->map(fn($ticket) => TicketResponseDto::fromEntity($ticket));
    }

    public function getTicket(string $ticketCode): TicketResponseDto
    {
        $ticket = $this->ticketRepository->findByCode($ticketCode);
        if (!$ticket || $ticket->reservation->user_id !== Auth::id()) {
            throw new \InvalidArgumentException('Ticket not found');
        }

        return TicketResponseDto::fromEntity($ticket);
    }

    public function transferTicket(TransferTicketRequestDto $dto): void
    {
        $ticket = $this->ticketRepository->findByCode($dto->getCode());
        
        if (!$ticket) {
            throw new ModelNotFoundException('Ticket not found');
        }

        if ($ticket->reservation->user_id !== Auth::id()) {
            throw new AuthorizationException('You are not authorized to transfer this ticket');
        }

        if (!$ticket->canBeTransferred()) {
            throw new \InvalidArgumentException('Ticket cannot be transferred');
        }

        // Transfer işlemi...
    }

    public function cancelTicket(string $ticketCode): bool
    {
        $ticket = $this->ticketRepository->findByCode($ticketCode);
        if (!$ticket || $ticket->reservation->user_id !== Auth::id()) {
            throw new \InvalidArgumentException('Ticket not found');
        }

        if (!$ticket->canBeCancelled()) {
            throw new \InvalidArgumentException('Ticket cannot be cancelled');
        }

        return $this->ticketRepository->cancel($ticketCode);
    }

    public function downloadTicket(string $ticketCode): string
    {
        $ticket = $this->ticketRepository->findByCode($ticketCode);
        if (!$ticket || $ticket->reservation->user_id !== Auth::id()) {
            throw new \InvalidArgumentException('Ticket not found');
        }
        
        $pdf = PDF::loadView('tickets.pdf', [
            'ticket' => TicketResponseDto::fromEntity($ticket)
        ]);

        return $pdf->output();
    }
} 