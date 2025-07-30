<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;
use App\Models\User;
use App\Models\Availability;
use Illuminate\Support\Carbon;

class AvailabilityWeekSeeder extends Seeder
{
    /**
     * Remplit les créneaux pour chaque service, chaque API, chaque jour de la semaine à partir du 2025-07-18.
     */
    public function run(): void
    {
        $services = Service::all();
        $apis = User::where('role', 'api')->get();
        $statuses = ['available', 'reserved', 'desactive'];
        $date = Carbon::parse('2025-07-18');

        // Générer sur 1 mois (31 jours)
        foreach (range(0, 30) as $day) {
            $currentDate = $date->copy()->addDays($day);
            // Ne générer que du lundi (1) au vendredi (5)
            if ($currentDate->dayOfWeek === 0 || $currentDate->dayOfWeek === 6) {
                continue;
            }
            foreach ($apis as $api) {
                for ($h = 9; $h < 16; $h++) {
                    $start = $currentDate->copy()->setTime($h, 0, 0);
                    $end = $currentDate->copy()->setTime($h + 1, 0, 0);
                    foreach ($services as $service) {
                        // Vérifier qu'il n'existe pas déjà un créneau pour cette API, ce service, ce jour, cette heure
                        $exists = Availability::where('user_id', $api->id)
                            ->where('service_id', $service->id)
                            ->whereDate('start_datetime', $currentDate->toDateString())
                            ->whereTime('start_datetime', $start->format('H:i:s'))
                            ->exists();
                        if ($exists) continue;
                        $status = $statuses[array_rand($statuses)];
                        Availability::create([
                            'service_id' => $service->id,
                            'user_id' => $api->id,
                            'start_datetime' => $start,
                            'end_datetime' => $end,
                            'status' => $status,
                        ]);
                    }
                }
            }
        }
    }
}
