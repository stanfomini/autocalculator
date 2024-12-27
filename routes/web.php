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

// Existing route definitions...

Route::get('/', function () {
    return view('welcome');
});

// Existing route for test
Route::resource('/test', TestItemController::class)->only(['index', 'store']);

// New route for /hello
Route::resource('/hello', HelloItemController::class)->only(['index', 'store']);

// Dashboard placeholder
Route::get('/dashboard', function () {
    return view('dashboard');
});

// Resourceful routes for the new booking feature
Route::resource('/booking', BookingController::class);

// SSE route for real-time listing of bookings
Route::get('/booking/sse', [BookingSseController::class, 'stream'])->name('booking.sse');