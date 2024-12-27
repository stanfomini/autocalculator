<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestItemController;
use App\Http\Controllers\HelloItemController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\BookingSseController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application.
|
*/

// Existing routes
Route::get('/', function () {
    return view('welcome');
});

Route::resource('/test', TestItemController::class)->only(['index', 'store']);
Route::resource('/hello', HelloItemController::class)->only(['index', 'store']);

// This is the new Bookings resource route
Route::resource('/booking', BookingController::class);

// SSE route for real-time Bookings
Route::get('/booking/sse', [BookingSseController::class, 'stream'])->name('booking.sse');