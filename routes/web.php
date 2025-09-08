<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ActiviteitenController;

// Publieke homepage: activiteitenlijst
Route::get('/', [ActiviteitenController::class, 'index'])->name('activiteiten.index');

// Gast-inschrijving (popup)
Route::post('/activiteiten/inschrijven/guest', [ActiviteitenController::class, 'guestSignup'])
    ->name('activiteiten.guest');

// Ingelogde inschrijving (geen popup)
Route::post('/activiteiten/inschrijven', [ActiviteitenController::class, 'authSignup'])
    ->middleware('auth')
    ->name('activiteiten.auth');

// Overige routes
Route::get('/dashboard', fn () => view('dashboard'))
    ->middleware(['auth','verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::middleware('role:user')->delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

require __DIR__.'/auth.php';
