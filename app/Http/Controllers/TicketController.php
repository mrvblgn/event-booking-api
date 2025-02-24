<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransferTicketRequest;
use App\Models\DTOs\Tickets\Requests\TransferTicketRequestDto;
use App\Services\Abstracts\ITicketService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class TicketController extends Controller
{
    use ApiResponse;

    public function __construct(
        private readonly ITicketService $ticketService
    ) {}

    public function index(): JsonResponse
    {
        $tickets = $this->ticketService->getUserTickets();
        return $this->success($tickets, 'Tickets retrieved successfully');
    }

    public function show(int $id): JsonResponse
    {
        $ticket = $this->ticketService->getTicket($id);
        return $this->success($ticket, 'Ticket retrieved successfully');
    }

    public function transfer(TransferTicketRequest $request, int $id): JsonResponse
    {
        $dto = TransferTicketRequestDto::fromRequest([
            'ticket_code' => $id,
            'email' => $request->validated()['email']
        ]);
        
        $this->ticketService->transferTicket($dto);
        return $this->success(null, 'Ticket transferred successfully');
    }

    public function cancel(string $ticketCode): JsonResponse
    {
        try {
            $success = $this->ticketService->cancelTicket($ticketCode);
            return response()->json([
                'message' => $success ? 'Ticket cancelled successfully' : 'Failed to cancel ticket'
            ], $success ? 200 : 400);
        } catch (\InvalidArgumentException $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function download(int $id): Response
    {
        $pdf = $this->ticketService->downloadTicket($id);
        
        return response($pdf, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="ticket.pdf"'
        ]);
    }
} 