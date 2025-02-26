<?php

namespace Database\Seeders;

use App\Models\Entities\Seat;
use App\Models\Entities\Event;
use Illuminate\Database\Seeder;

class SeatSeeder extends Seeder
{
    public function run(): void
    {
        $events = Event::all();

        foreach ($events as $event) {
            // VIP Koltuklar (A-C sıraları)
            foreach (range('A', 'C') as $row) {
                foreach (range(1, 20) as $number) {
                    Seat::create([
                        'event_id' => $event->id,
                        'venue_id' => $event->venue_id,
                        'section' => 'VIP',
                        'row' => $row,
                        'number' => $number,
                        'price' => 500.00,
                        'status' => 'available'
                    ]);
                }
            }

            // Normal Koltuklar (D-J sıraları)
            foreach (range('D', 'J') as $row) {
                foreach (range(1, 30) as $number) {
                    Seat::create([
                        'event_id' => $event->id,
                        'venue_id' => $event->venue_id,
                        'section' => 'Regular',
                        'row' => $row,
                        'number' => $number,
                        'price' => 200.00,
                        'status' => 'available'
                    ]);
                }
            }
        }
    }
} 