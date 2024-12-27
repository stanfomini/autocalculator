<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestItemController;
use App\Http\Controllers\HelloItemController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\ScheduleSseController;

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

// Existing test routes
Route::resource('/test', TestItemController::class)->only(['index', 'store']);
Route::resource('/hello', HelloItemController::class)->only(['index', 'store']);

// New resource route for scheduling, at /testing1
Route::resource('/testing1', ScheduleController::class);

// SSE route for real-time updates
Route::get('/testing1/sse', [ScheduleSseController::class, 'stream'])->name('testing1.sse');