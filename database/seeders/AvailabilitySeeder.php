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
        $apis = \App\Models\User::where('role', 'api')->get();
        if ($apis->isEmpty()) {
            // Si aucun API, on ne seed pas
            return;
        }
        // Pour chaque service, créer 10 créneaux de disponibilité réalistes, répartis entre les API
        foreach (Service::all() as $service) {
            for ($i = 0; $i < 10; $i++) {
                $api = $apis->random();
                $start = now()->addDays(rand(1, 30))->setTime(rand(8, 16), [0, 30][rand(0, 1)]);
                $end = (clone $start)->addMinutes($service->duration_minutes);
                Availability::create([
                    'service_id' => $service->id,
                    'user_id' => $api->id,
                    'start_datetime' => $start,
                    'end_datetime' => $end,
                ]);
            }
        }
    }
}
