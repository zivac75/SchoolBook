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
        // On ne fait réserver que les étudiants
        $etudiants = \App\Models\User::where('role', 'etudiant')->get();
        $availabilities = Availability::all();
        foreach ($etudiants as $user) {
            $userAvailabilities = $availabilities->random(min(2, $availabilities->count()));
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
