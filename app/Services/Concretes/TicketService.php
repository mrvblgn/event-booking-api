<?php

namespace App\Services\Concretes;

use App\Models\Dtos\Tickets\Requests\TransferTicketRequestDto;
use App\Models\Dtos\Tickets\Responses\TicketResponseDto;
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
        return $this->ticketRepository->getUserTickets(auth()->id());
    }

    public function getTicket(string $ticketCode): TicketResponseDto
    {
        $ticket = $this->ticketRepository->findByCode($ticketCode);
        if (!$ticket || $ticket->reservation->user_id !== Auth::id()) {
            throw new \InvalidArgumentException('Ticket not found');
        }
        return TicketResponseDto::fromEntity($ticket);
    }

    public function transferTicket(TransferTicketRequestDto $dto): bool
    {
        try {
            $ticket = $this->ticketRepository->findByCode($dto->getCode());
            
            if (!$ticket->canBeTransferred()) {
                throw new \Exception('Ticket cannot be transferred');
            }

            return $this->ticketRepository->transferTicket($ticket, $dto->getEmail());
        } catch (ModelNotFoundException $e) {
            throw new \Exception('Ticket not found');
        }
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
        return "ticket_{$ticketCode}.pdf";
    }
} 