<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Service;
use App\Models\Availability;

class AvailabilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pour chaque service, créer 10 créneaux de disponibilité réalistes
        foreach (Service::all() as $service) {
            for ($i = 0; $i < 10; $i++) {
                $start = now()->addDays(rand(1, 30))->setTime(rand(8, 16), [0, 30][rand(0, 1)]);
                $end = (clone $start)->addMinutes($service->duration_minutes);
                Availability::create([
                    'service_id' => $service->id,
                    'start_datetime' => $start,
                    'end_datetime' => $end,
                    'capacity' => rand(1, 8),
                ]);
            }
        }
    }
}
