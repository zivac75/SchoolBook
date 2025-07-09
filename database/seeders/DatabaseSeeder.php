<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            ServiceSeeder::class,
        ]);
        // Création des utilisateurs
        \App\Models\User::factory(20)->create();
        $this->call([
            AvailabilitySeeder::class,
            ReservationSeeder::class,
            NotificationSeeder::class,
        ]);
    }
}
