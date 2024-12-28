<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AwesomeCalcController;

// We keep the existing routes but add a new ?awesome? page route:
Route::get('/awesome', [AwesomeCalcController::class, 'spaIndex'])->name('awesome.spa');

// You might have an API route for actual CRUD in routes/api.php, but if we do it here:
Route::prefix('awesome')->group(function () {
    Route::get('/{id}', [AwesomeCalcController::class, 'show'])->name('awesome.show');
    // The SPA might load an individual record in the form
});