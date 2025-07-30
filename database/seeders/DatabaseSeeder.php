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
            AdminSeeder::class,
            UserSeeder::class,
            ServiceSeeder::class,
            AvailabilitySeeder::class,
            ReservationSeeder::class,
            NotificationSeeder::class,
            AvailabilityWeekSeeder::class, // Ajout du seeder de créneaux semaine
        ]);
    }
}
