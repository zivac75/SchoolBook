<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Service;
use App\Models\Availability;
use App\Models\Reservation;

class ReservationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pour chaque utilisateur, créer quelques réservations aléatoires
        $users = User::all();
        $availabilities = Availability::all();
        foreach ($users as $user) {
            $userAvailabilities = $availabilities->random(rand(2, 4));
            foreach ($userAvailabilities as $availability) {
                Reservation::create([
                    'user_id' => $user->id,
                    'service_id' => $availability->service_id,
                    'availability_id' => $availability->id,
                    'status' => ['pending', 'confirmed', 'cancelled'][rand(0, 2)],
                ]);
            }
        }
    }
}
