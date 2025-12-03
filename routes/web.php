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

// USER Controllers
use App\Http\Controllers\User\FilmController as UserFilmController;
use App\Http\Controllers\User\UserDashboardController;

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
Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/dashboard-user', [UserDashboardController::class, 'index'])
        ->name('user.dashboard');
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

        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');

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

require __DIR__.'/auth.php';
