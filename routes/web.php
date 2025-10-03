<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ActiviteitenController;
use App\Http\Controllers\AdminActiviteitenController;


Route::get('/', [ActiviteitenController::class, 'index'])
    ->name('activiteiten.index');

Route::post('/activiteiten/inschrijven/guest', [ActiviteitenController::class, 'guestSignup'])
    ->middleware('throttle:10,1')
    ->name('activiteiten.guest');




Route::middleware(['auth'])->group(function () {
    // Admin dashboard
    Route::get('/admin/activities', [AdminController::class, 'createActiviteit'])->name('admin.activities.create');

    // Activiteit opslaan
    Route::post('/admin/activities', [AdminController::class, 'store'])->name('admin.activities.store');
});


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    // Profiel
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/photo', [ProfileController::class, 'updatePhoto'])->name('profile.photo.update');
    Route::middleware('role:user')->delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // In-/uitschrijven activiteiten (voor ingelogde users)
    Route::post('/activiteiten/inschrijven', [ActiviteitenController::class, 'authSignup'])->name('activiteiten.auth');
    Route::delete('/activiteiten/uitschrijven', [ActiviteitenController::class, 'unsubscribe'])->name('activiteiten.unsubscribe');

    // Admin: activiteiten (alleen admins)
    Route::middleware('auth')->group(function () {
        Route::resource('/admin/activiteiten', AdminActiviteitenController::class)->names('admin.activiteiten');

        // NIEUW: meerdere foto’s uploaden
        Route::post('/admin/activiteiten/{activity}/photos', [AdminActiviteitenController::class, 'photosUpload'])
            ->whereNumber('activity')->name('admin.activiteiten.photos.upload');

        // NIEUW: meerdere foto’s verwijderen
        Route::delete('/admin/activiteiten/{activity}/photos', [AdminActiviteitenController::class, 'photosDelete'])
            ->whereNumber('activity')->name('admin.activiteiten.photos.delete');

        // OUD: single upload (route moet naar uploadPhoto, niet updatePhoto)
        Route::post('/admin/activiteiten/{activity}/photo', [AdminActiviteitenController::class, 'uploadPhoto'])
            ->whereNumber('activity')->name('admin.activiteiten.photo');
    });
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

// Delete user:
Route::delete('/admin/users/{id}', [UserController::class, 'destroy'])->name('admin.deleteUser');

require __DIR__ . '/auth.php';
