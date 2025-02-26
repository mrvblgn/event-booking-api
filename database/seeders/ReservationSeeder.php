<?php

namespace Database\Seeders;

use App\Models\Entities\User;
use App\Models\Entities\Event;
use App\Models\Entities\Reservation;
use App\Models\Entities\ReservationItem;
use Illuminate\Database\Seeder;

class ReservationSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('is_admin', false)->get();
        $events = Event::all();

        foreach ($users as $user) {
            // Her kullanıcı için 2 rezervasyon oluştur
            foreach ($events as $event) {
                // Müsait koltukları al
                $availableSeats = $event->seats()
                    ->where('status', 'available')
                    ->take(2)
                    ->get();

                if ($availableSeats->count() >= 2) {
                    // Rezervasyon oluştur
                    $reservation = Reservation::create([
                        'user_id' => $user->id,
                        'event_id' => $event->id,
                        'status' => 'pending',
                        'total_amount' => $availableSeats->sum('price'),
                        'expires_at' => now()->addHours(2)
                    ]);

                    // Rezervasyon kalemleri oluştur
                    foreach ($availableSeats as $seat) {
                        ReservationItem::create([
                            'reservation_id' => $reservation->id,
                            'seat_id' => $seat->id,
                            'price' => $seat->price
                        ]);

                        // Koltuğu rezerve et
                        $seat->update(['status' => 'reserved']);
                    }
                }
            }
        }
    }
} 