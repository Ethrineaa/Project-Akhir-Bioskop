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
use App\Http\Controllers\Admin\PemesananController as AdminPemesananController;
use App\Http\Controllers\Admin\PembayaranController as AdminPembayaranController;
use App\Http\Controllers\Admin\AdminDashboardController;

// USER Controllers
use App\Http\Controllers\User\FilmController as UserFilmController;
use App\Http\Controllers\User\UserDashboardController;
use App\Http\Controllers\User\PemesananController as PemesananController;
use App\Http\Controllers\User\PembayaranController as PembayaranController;

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
    ->middleware(['auth', 'role:user'])
    ->group(function () {
        Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');

        // =============================
        // PILIH KURSI
        // =============================
        Route::get('kursi/{jadwal}', [PemesananController::class, 'pilihKursi'])->name('kursi.index');

        // =============================
        // PEMESANAN
        // =============================
        Route::resource('pemesanan', PemesananController::class)->only(['store', 'index', 'show']);

        Route::get('pemesanan/{pemesanan}/payment', [PemesananController::class, 'payment'])->name('pemesanan.payment');

        // =============================
        // PEMBAYARAN
        // =============================
        Route::post('pembayaran/{pembayaran}', [PembayaranController::class, 'store'])->name('pembayaran.store');

        Route::get('/pembayaran/{id}', [PembayaranController::class, 'pay'])->name('pembayaran.pay');
    });

/*
|-------------------------------------------------------------------2-------
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
        Route::resource('pemesanan', AdminPemesananController::class);
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
