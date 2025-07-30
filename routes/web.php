<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CodeController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\AvailabilityController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

// Routes d'authentification
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'loginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
    Route::get('register', [AuthController::class, 'registerForm'])->name('register');
    Route::post('register', [AuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    // Routes pour la gestion des codes d'inscription (admin uniquement)
    // Route::middleware('isAdmin')->group(function () {
    Route::get('codes', [CodeController::class, 'index'])->name('codes.index');
    Route::get('codes/create', [CodeController::class, 'create'])->name('codes.create');
    Route::post('codes', [CodeController::class, 'store'])->name('codes.store');
    Route::patch('codes/{code}/toggle', [CodeController::class, 'toggleStatus'])->name('codes.toggle');
    // });

    // Routes pour la gestion des services (admin uniquement)
    Route::middleware(['auth'])->group(function () {
        Route::resource('services', ServiceController::class);
        Route::resource('availabilities', AvailabilityController::class);
    });

    Route::post('availabilities/update-week', [\App\Http\Controllers\AvailabilityController::class, 'updateWeek'])->name('availabilities.updateWeek');

    // Routes pour la gestion des réservations
    Route::get('reservations/create/{availability}', [\App\Http\Controllers\ReservationController::class, 'create'])->name('reservations.create');
    Route::post('reservations', [\App\Http\Controllers\ReservationController::class, 'store'])->name('reservations.store');
    Route::get('profile', [\App\Http\Controllers\ReservationController::class, 'profile'])->name('profile');
    Route::delete('reservations/{reservation}', [\App\Http\Controllers\ReservationController::class, 'destroy'])->name('reservations.destroy');
    Route::get('reserver', [\App\Http\Controllers\ReservationController::class, 'wizard'])->name('reservations.wizard');
    Route::post('reserver', [\App\Http\Controllers\ReservationController::class, 'wizardStore'])->name('reservations.wizard.store');

    // Route du tableau de bord
    Route::get('/dashboard', function (\Illuminate\Http\Request $request) {
        if (!$request->user() || $request->user()->role !== 'admin') {
            abort(403, 'Accès non autorisé.');
        }
        $apis = \App\Models\User::where('role', 'api')->orderBy('name')->get();
        $apiId = $request->query('api_id');
        $selectedApi = $apiId ? $apis->where('id', $apiId)->first() : null;
        $appointments = $selectedApi ? ($selectedApi->appointment ?? []) : [];
        $services = \App\Models\Service::orderBy('name')->get();

        // Statistiques de réservation
        $totalReservations = \App\Models\Reservation::count();
        $reservationsByStatus = [
            'pending' => \App\Models\Reservation::where('status', 'pending')->count(),
            'confirmed' => \App\Models\Reservation::where('status', 'confirmed')->count(),
            'cancelled' => \App\Models\Reservation::where('status', 'cancelled')->count(),
        ];

        $reservationsByService = \App\Models\Service::withCount('reservations')->get()
            ->mapWithKeys(function ($service) {
                return [$service->name => $service->reservations_count];
            });

        // Réservations par période
        $today = \Carbon\Carbon::today();
        $startOfWeek = \Carbon\Carbon::now()->startOfWeek();
        $startOfMonth = \Carbon\Carbon::now()->startOfMonth();

        $reservationsByPeriod = [
            'today' => \App\Models\Reservation::whereDate('created_at', $today)->count(),
            'this_week' => \App\Models\Reservation::whereBetween('created_at', [$startOfWeek, now()])->count(),
            'this_month' => \App\Models\Reservation::whereBetween('created_at', [$startOfMonth, now()])->count(),
        ];

        // Taux d'occupation
        $totalAvailabilities = \App\Models\Availability::count();
        $reservedAvailabilities = \App\Models\Availability::where('status', 'reserved')->count();
        $availableAvailabilities = \App\Models\Availability::where('status', 'available')->count();

        $occupancyRate = [
            'reserved' => $totalAvailabilities > 0 ? round(($reservedAvailabilities / $totalAvailabilities) * 100, 1) : 0,
            'available' => $totalAvailabilities > 0 ? round(($availableAvailabilities / $totalAvailabilities) * 100, 1) : 0,
        ];

        return view('dashboard', compact(
            'apis',
            'apiId',
            'selectedApi',
            'appointments',
            'services',
            'totalReservations',
            'reservationsByStatus',
            'reservationsByService',
            'reservationsByPeriod',
            'occupancyRate'
        ));
    })->name('dashboard');

    Route::get('/dashboard/api', function (\Illuminate\Http\Request $request) {
        $user = $request->user();
        if (!$user || $user->role !== 'api') {
            abort(403, 'Accès non autorisé.');
        }
        // Récupérer les réservations sur les créneaux appartenant à cet API
        $appointments = \App\Models\Reservation::with(['user', 'availability.service'])
            ->whereHas('availability', fn($q) => $q->where('user_id', $user->id))
            ->orderByDesc('availability_id')
            ->get();
        $totalAppointments = $appointments->count();
        $appointmentsByStatus = [
            'confirmed' => $appointments->where('status', 'confirmed')->count(),
            'pending'   => $appointments->where('status', 'pending')->count(),
            'cancelled' => $appointments->where('status', 'cancelled')->count(),
        ];
        return view('dashboard.api', compact('appointments', 'totalAppointments', 'appointmentsByStatus'));
    })->name('dashboard.api');
    Route::get('/dashboard/etudiant', [\App\Http\Controllers\ReservationController::class, 'dashboardEtudiant'])->name('dashboard.etudiant');
});
