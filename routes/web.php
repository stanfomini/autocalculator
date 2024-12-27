<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\YesTestController;
use App\Http\Controllers\YesTestSseController;

Route::get('/', function () {
    return view('welcome');
});

// Real-time scheduling at /yestest
Route::resource('/yestest', YesTestController::class)
    ->parameters(['yestest' => 'yestest']);

// SSE route for real-time
Route::get('/yestest/sse', [YesTestSseController::class, 'stream'])->name('yestest.sse');