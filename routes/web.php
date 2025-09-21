<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
require __DIR__.'/auth.php';

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

// Admin dashboard:
Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

// Register user page:
Route::get('/admin/registerUser', [UserController::class, 'show'])->name('admin.registerUser');

// Creating user from form:
Route::post('/admin/registerUser', [UserController::class, 'store'])->name('registerAccount.send');

// Accounts page:
Route::get('/admin/accounts', [UserController::class, 'index'])->name('admin.acounts');

// Profile admin page:
Route::get('/admin/profile/{id}', [UserController::class, 'profile'])->name('admin.profile');

// Update profile page:
Route::post('/admin/registerAccount/{id}', [UserController::class, 'update'])->name('registerAccount.update');

