<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CodeController;
use App\Http\Controllers\ServiceController;
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
    Route::middleware(['auth', 'role:admin'])->group(function () {
        Route::resource('services', ServiceController::class);
    });

    // Route du tableau de bord
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/dashboard/api', function () {
        return view('dashboard.api');
    })->name('dashboard.api');
    Route::get('/dashboard/etudiant', function () {
        return view('dashboard.etudiant');
    })->name('dashboard.etudiant');
});
