<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestItemController;
use App\Http\Controllers\HelloItemController;
use App\Http\Controllers\AppointmentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application.
|
*/

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

// New route for /schedule
Route::get('/schedule', [AppointmentController::class, 'index'])->name('appointments.index');
Route::post('/schedule', [AppointmentController::class, 'store'])->name('appointments.store');