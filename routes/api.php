<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MidtransController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| Route ini TIDAK pakai CSRF & SESSION
| Cocok untuk Midtrans Callback
|--------------------------------------------------------------------------
*/

Route::post('/midtrans/callback', [MidtransController::class, 'callback']);
