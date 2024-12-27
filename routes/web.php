<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestItemController;
use App\Http\Controllers\HelloItemController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\AppointmentSseController;

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

// Resourceful routes for appointments
// This gives us index, create, store, show, edit, update, destroy
Route::resource('/schedule', AppointmentController::class);

// SSE route for real-time appointment listing
Route::get('/appointments/sse', [AppointmentSseController::class, 'stream'])->name('appointments.sse');