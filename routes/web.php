<?php
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\GenreController;
use App\Http\Controllers\Admin\FilmController;
use App\Http\Controllers\Admin\StudioController;
use App\Http\Controllers\Admin\KursiController;
use App\Http\Controllers\Admin\JadwalController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\UserDashboardController;

Route::get('/', function () {
    return view('admin.layouts.app');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth'])
    ->group(function () {
        Route::resource('genre', GenreController::class);
        Route::resource('film', FilmController::class);
        Route::resource('studio', StudioController::class);
        Route::resource('kursi', KursiController::class);
        Route::resource('jadwal', JadwalController::class);
    });

// public landing page
Route::get('/', [LandingController::class, 'index'])->name('landing');

// user dashboard (login required)
Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/dashboard-user', [UserDashboardController::class, 'index'])->name('user.dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
