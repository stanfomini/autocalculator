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

// Route for the Appointment scheduler (SPA style)
Route::get('/schedule', [AppointmentController::class, 'index'])->name('appointments.index');
Route::post('/schedule', [AppointmentController::class, 'store'])->name('appointments.store');

// Server-Sent Event (SSE) route for real-time appointments
Route::get('/appointments/sse', [AppointmentSseController::class, 'stream'])->name('appointments.sse');