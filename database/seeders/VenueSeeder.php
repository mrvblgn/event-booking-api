<?php

namespace Database\Seeders;

use App\Models\Entities\Venue;
use Illuminate\Database\Seeder;

class VenueSeeder extends Seeder
{
    public function run(): void
    {
        Venue::create([
            'name' => 'Zorlu PSM',
            'address' => 'Levazım, Koru Sokağı No:2, 34340 Beşiktaş/İstanbul',
            'capacity' => 2500
        ]);

        Venue::create([
            'name' => 'Volkswagen Arena',
            'address' => 'Huzur Mah. Maslak Ayazağa Cad. No:4/A, 34396 Sarıyer/İstanbul',
            'capacity' => 5000
        ]);
    }
}
