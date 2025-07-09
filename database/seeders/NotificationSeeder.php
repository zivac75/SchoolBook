<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Reservation;
use App\Models\Notification;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pour chaque réservation, créer une notification de type aléatoire
        foreach (Reservation::all() as $reservation) {
            Notification::create([
                'reservation_id' => $reservation->id,
                'type' => ['confirmation', 'reminder', 'cancellation'][rand(0, 2)],
                'sent_at' => now()->subDays(rand(0, 7)),
            ]);
        }
    }
}
