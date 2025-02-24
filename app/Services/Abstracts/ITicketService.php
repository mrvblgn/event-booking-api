<?php

namespace App\Services\Abstracts;

use App\Models\DTOs\Tickets\Requests\TransferTicketRequestDto;
use App\Models\DTOs\Tickets\Responses\TicketResponseDto;
use Illuminate\Support\Collection;

interface ITicketService
{
    public function getUserTickets(): Collection;
    public function getTicket(string $ticketCode): TicketResponseDto;
    public function transferTicket(TransferTicketRequestDto $dto): bool;
    public function cancelTicket(string $ticketCode): bool;
    public function downloadTicket(string $ticketCode): string; 
} 