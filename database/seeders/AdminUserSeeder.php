<?php

namespace Database\Seeders;

use App\Models\Entities\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'is_admin' => true,
        ]);

        User::create([
            'name' => 'Merve Bilgin',
            'email' => 'merve@example.com',
            'password' => Hash::make('password'),
            'is_admin' => false,
        ]);
    }
}
