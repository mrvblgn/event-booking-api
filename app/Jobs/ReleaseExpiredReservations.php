<?php

namespace App\Jobs;

use App\Models\Reservation;
use App\Models\Seat;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class ReleaseExpiredReservations implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        $expiredReservations = Reservation::where('status', 'pending')
            ->where('expires_at', '<', now())
            ->get();

        foreach ($expiredReservations as $reservation) {
            DB::transaction(function () use ($reservation) {
                // Rezervasyonu iptal et
                $reservation->update(['status' => 'expired']);
                
                // Koltukları serbest bırak
                $seatIds = $reservation->reservationItems->pluck('seat_id');
                Seat::whereIn('id', $seatIds)->update(['status' => 'available']);
            });
        }
    }
} 