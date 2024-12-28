<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\YesTestController;
use App\Http\Controllers\YesTestSseController;
// If you still want the schedule-based resource, you can keep it or remove it.
// We'll assume you're focusing on /yestest now.

// Real-time scheduling at /yestest
Route::resource('/yestest', YesTestController::class)
    ->parameters(['yestest' => 'yestest']);

// SSE route for real-time
Route::get('/yestest/sse', [YesTestSseController::class, 'stream'])->name('yestest.sse');