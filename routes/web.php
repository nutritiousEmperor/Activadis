<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;

Route::middleware(['auth'])->group(function () {
    // Activiteiten overzicht
    Route::get('/admin/activiteiten', [AdminController::class, 'activiteiten'])->name('admin.activiteiten');

    // Nieuwe activiteit aanmaken
    Route::get('/admin/createActivities', [AdminController::class, 'index'])->name('admin.create');

    // Opslaan van activiteit
    Route::post('/admin/activities', [AdminController::class, 'store'])->name('admin.activities.store');
});


// Publieke routes
Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


// Profiel routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::middleware('role:user')->delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
