<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Models\Reservation;
use App\Models\Notification;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReservationMail;
use Carbon\Carbon;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Envoyer un email de rappel un jour avant chaque rendez-vous
        $schedule->call(function () {
            // Récupérer toutes les réservations de demain
            $tomorrow = Carbon::tomorrow();
            $reservations = Reservation::whereHas('availability', function ($query) use ($tomorrow) {
                $query->whereDate('start_datetime', $tomorrow->toDateString());
            })->with(['user', 'service', 'availability'])->get();

            foreach ($reservations as $reservation) {
                // Vérifier si un rappel a déjà été envoyé
                $alreadySent = Notification::where('reservation_id', $reservation->id)
                    ->where('type', 'reminder')
                    ->exists();

                if (!$alreadySent) {
                    // Envoyer l'email de rappel
                    Mail::to($reservation->user->email)
                        ->send(new ReservationMail($reservation, 'rappel'));

                    // Enregistrer la notification
                    Notification::create([
                        'reservation_id' => $reservation->id,
                        'type' => 'reminder',
                        'sent_at' => now(),
                    ]);
                }
            }
        })->dailyAt('08:00');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
