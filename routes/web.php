<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\ProfileController;

// ADMIN Controllers
use App\Http\Controllers\Admin\GenreController;
use App\Http\Controllers\Admin\FilmController as AdminFilmController;
use App\Http\Controllers\Admin\StudioController;
use App\Http\Controllers\Admin\KursiController;
use App\Http\Controllers\Admin\JadwalController;
use App\Http\Controllers\Admin\AdminDashboardController;

// USER Controllers
use App\Http\Controllers\User\FilmController as UserFilmController;
use App\Http\Controllers\User\UserDashboardController;
use App\Http\Controllers\User\PemesananController;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/

// Landing page
Route::get('/', [LandingController::class, 'index'])->name('landing');

// Detail + jadwal film (dapat dilihat tanpa login)
Route::get('/film/{film}', [UserFilmController::class, 'show'])->name('film.show');

/*
|--------------------------------------------------------------------------
| USER ROUTES
|--------------------------------------------------------------------------
*/
Route::prefix('user')
    ->name('user.')
    ->middleware(['auth'])
    ->group(function () {
        Route::get('/pemesanan/kursi/{jadwal}', [PemesananController::class, 'pilihKursi'])->name('pemesanan.kursi');

        Route::post('/pemesanan/store', [PemesananController::class, 'store'])->name('pemesanan.store');

        Route::get('/pemesanan', [PemesananController::class, 'index'])->name('pemesanan.index');

        Route::get('/pemesanan/{id}', [PemesananController::class, 'show'])->name('pemesanan.show');
    });

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'role:admin'])
    ->group(function () {
        // Dashboard (menggunakan controller)
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        // CRUD
        Route::resource('genre', GenreController::class);
        Route::resource('film', AdminFilmController::class);
        Route::resource('studio', StudioController::class);
        Route::resource('kursi', KursiController::class);
        Route::resource('jadwal', JadwalController::class);
    });

/*
|--------------------------------------------------------------------------
| PROFILE ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Auth (login, register, logout)
require __DIR__ . '/auth.php';
