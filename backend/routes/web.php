<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ReservationsController;

Route::get('/', function () {
    return view('home');
});

// Route pour traiter les réservations
Route::post('/api/reservations', [ReservationsController::class, 'store']);

// Routes Admin
Route::prefix('admin')->name('admin.')->group(function () {
    // Connexion
    Route::get('login', [AdminController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AdminController::class, 'login'])->name('login.submit');

    // Dashboard (protégé)
    Route::get('dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::patch('reservations/{reservation}/status', [AdminController::class, 'updateReservationStatus'])->name('reservations.status');
    Route::delete('reservations/{reservation}', [AdminController::class, 'destroyReservation'])->name('reservations.destroy');

    // Déconnexion
    Route::post('logout', [AdminController::class, 'logout'])->name('logout');
});
