<?php

namespace Database\Seeders;

use App\Models\Entities\Ticket;
use App\Models\Entities\Reservation;
use App\Models\Entities\Seat;
use Illuminate\Database\Seeder;

class TicketSeeder extends Seeder
{
    public function run(): void
    {
        // Pending durumundaki bazı rezervasyonları al
        $reservations = Reservation::where('status', 'pending')
            ->take(3)  // İlk 3 rezervasyonu bilete çevir
            ->get();

        foreach ($reservations as $reservation) {
            // Rezervasyonu confirmed yap
            $reservation->update(['status' => 'confirmed']);

            // Rezervasyon kalemlerini bilete çevir
            foreach ($reservation->items as $item) {
                Ticket::create([
                    'reservation_id' => $reservation->id,
                    'seat_id' => $item->seat_id,
                    'status' => 'active'
                    // ticket_code otomatik oluşturuluyor (boot metodunda)
                ]);

                // Koltuğu sold durumuna çevir
                $item->seat->update(['status' => 'sold']);
            }
        }
    }
} 