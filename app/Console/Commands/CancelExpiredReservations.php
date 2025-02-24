<?php

namespace App\Console\Commands;

use App\Repositories\Abstracts\IReservationRepository;
use Illuminate\Console\Command;

class CancelExpiredReservations extends Command
{
    protected $signature = 'reservations:cancel-expired';
    protected $description = 'Cancel all expired reservations';

    public function handle(IReservationRepository $reservationRepository)
    {
        $expiredReservations = $reservationRepository->getExpiredReservations();
        
        foreach ($expiredReservations as $reservation) {
            $reservationRepository->cancel($reservation->id);
        }

        $this->info('Expired reservations cancelled: ' . $expiredReservations->count());
    }
} 