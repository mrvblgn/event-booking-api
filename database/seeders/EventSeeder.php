<?php

namespace Database\Seeders;

use App\Models\Entities\Event;
use App\Models\Entities\Venue;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        $venues = Venue::all();

        foreach ($venues as $venue) {
            Event::create([
                'name' => 'Rock Festival 2024',
                'description' => 'The biggest rock festival of the year',
                'venue_id' => $venue->id,
                'start_date' => now()->addDays(30),
                'end_date' => now()->addDays(30)->addHours(6),
                'status' => 'published'
            ]);

            Event::create([
                'name' => 'Jazz Night',
                'description' => 'A night of smooth jazz',
                'venue_id' => $venue->id,
                'start_date' => now()->addDays(45),
                'end_date' => now()->addDays(45)->addHours(3),
                'status' => 'published'
            ]);

            Event::create([
                'name' => 'Classical Symphony',
                'description' => 'Beethoven\'s 9th Symphony',
                'venue_id' => $venue->id,
                'start_date' => now()->addDays(60),
                'end_date' => now()->addDays(60)->addHours(2),
                'status' => 'draft'
            ]);
        }
    }
} 