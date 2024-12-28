<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\YesTestController;
use App\Http\Controllers\YesTestSseController;

// Real-time scheduling at /yestest
// Ensure parameter naming is consistent and we haven't mismatched route params.
Route::resource('yestest', YesTestController::class)
     ->parameters(['yestest' => 'yestest']);

// SSE route for real-time updates
Route::get('/yestest/sse', [YesTestSseController::class, 'stream'])->name('yestest.sse');