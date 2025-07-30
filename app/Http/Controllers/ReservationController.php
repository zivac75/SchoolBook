<?php

namespace App\Http\Controllers;

use App\Models\Availability;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Events\ReservationConfirmed;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReservationMail;
use App\Models\Notification;

class ReservationController extends Controller
{
    /**
     * Affiche le résumé du créneau à réserver.
     */
    public function create(Availability $availability)
    {
        if ($availability->status !== 'available') {
            return redirect()->route('availabilities.index')->with('error', 'Ce créneau n\'est plus disponible.');
        }
        return view('reservations.create', compact('availability'));
    }

    /**
     * Enregistre la réservation (transaction).
     */
    public function store(Request $request)
    {
        $request->validate([
            'availability_id' => ['required', 'exists:availabilities,id'],
        ]);
        $user = Auth::user();
        $availability = Availability::lockForUpdate()->findOrFail($request->availability_id);

        // Vérifier la limite de 3 réservations actives
        $activeCount = Reservation::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'confirmed'])
            ->count();
        if ($activeCount >= 3) {
            return back()->with('error', 'Vous avez déjà 3 réservations actives.');
        }

        // Vérifier la disponibilité
        if ($availability->status !== 'available') {
            return back()->with('error', 'Ce créneau n\'est plus disponible.');
        }

        $reservation = Reservation::create([
            'user_id' => $user->id,
            'service_id' => $availability->service_id,
            'availability_id' => $availability->id,
            'status' => 'pending',
        ]);
        $availability->update(['status' => 'reserved']);

        // ENVOI DU MAIL DE CONFIRMATION
        Mail::to($user->email)->send(new ReservationMail($reservation, 'confirmation'));
        // ENREGISTREMENT DE LA NOTIFICATION
        Notification::create([
            'reservation_id' => $reservation->id,
            'type' => 'confirmation',
            'sent_at' => now(),
        ]);

        return redirect()->route('dashboard.etudiant')->with('success', 'Réservation enregistrée.');
    }

    /**
     * Affiche le profil utilisateur avec ses réservations.
     */
    public function profile()
    {
        $user = Auth::user();
        $reservations = Reservation::with('availability.service')
            ->where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->get();
        return view('profile', compact('reservations'));
    }

    /**
     * Annule une réservation (transaction).
     */
    public function destroy(Reservation $reservation)
    {
        $user = Auth::user();
        if ($reservation->user_id !== $user->id) {
            abort(403);
        }

        // Garder une référence à la réservation pour l'email
        $reservationCopy = clone $reservation;
        $reservationCopy->load('service', 'availability');

        DB::transaction(function () use ($reservation) {
            $reservation->availability->update(['status' => 'available']);
            $reservation->delete();
        });

        // ENVOI DU MAIL D'ANNULATION
        Mail::to($user->email)->send(new ReservationMail($reservationCopy, 'annulation'));

        return back()->with('success', 'Réservation annulée.');
    }

    /**
     * Wizard de réservation pour étudiant (multi-étapes)
     */
    public function wizard(Request $request)
    {
        $user = auth()->user();
        $step = $request->input('step', 1);
        $serviceId = $request->input('service_id');
        $apiId = $request->input('api_id');
        $availabilityId = $request->input('availability_id');
        $services = \App\Models\Service::orderBy('name')->get();
        $apis = collect();
        $availabilities = collect();
        $selectedAvailability = null;
        if ($serviceId && $step >= 2) {
            $apis = \App\Models\User::where('role', 'api')
                ->whereHas('availabilities', function ($q) use ($serviceId) {
                    $q->where('service_id', $serviceId)->where('status', 'available');
                })
                ->orderBy('name')->get();
        }
        if ($serviceId && $apiId && $step >= 3) {
            $availabilities = \App\Models\Availability::with('api')
                ->where('service_id', $serviceId)
                ->where('user_id', $apiId)
                ->where('status', 'available')
                ->orderBy('start_datetime')
                ->get();
        }
        if ($availabilityId) {
            $selectedAvailability = \App\Models\Availability::with('service', 'api')->find($availabilityId);
        }
        return view('reservations.wizard', compact('step', 'services', 'serviceId', 'apis', 'apiId', 'availabilities', 'availabilityId', 'selectedAvailability'));
    }

    /**
     * Traite la réservation depuis le wizard
     */
    public function wizardStore(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'service_id' => ['required', 'exists:services,id'],
            'availability_id' => ['required', 'exists:availabilities,id'],
        ]);
        $availability = \App\Models\Availability::findOrFail($request->availability_id);

        // Vérifier la disponibilité
        if ($availability->status !== 'available') {
            return redirect()->route('reservations.wizard', ['step' => 2, 'service_id' => $request->service_id])->with('error', 'Ce créneau n\'est plus disponible.');
        }
        // Vérifier la limite de 3 réservations actives
        $activeCount = \App\Models\Reservation::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'confirmed'])
            ->count();
        if ($activeCount >= 3) {
            return redirect()->route('dashboard.etudiant')->with('error', 'Vous avez déjà 3 réservations actives.');
        }

        $reservation = \App\Models\Reservation::create([
            'user_id' => $user->id,
            'service_id' => $availability->service_id,
            'availability_id' => $availability->id,
            'status' => 'pending',
        ]);
        $availability->update(['status' => 'reserved']);

        // ENVOI DU MAIL DE CONFIRMATION
        Mail::to($user->email)->send(new ReservationMail($reservation, 'confirmation'));
        // ENREGISTREMENT DE LA NOTIFICATION
        Notification::create([
            'reservation_id' => $reservation->id,
            'type' => 'confirmation',
            'sent_at' => now(),
        ]);

        return redirect()->route('dashboard.etudiant')->with('success', 'Réservation enregistrée.');
    }

    public function dashboardEtudiant()
    {
        $user = auth()->user();
        $reservations = Reservation::with('availability.service')
            ->where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->get();
        return view('dashboard.etudiant', compact('reservations'));
    }
}
