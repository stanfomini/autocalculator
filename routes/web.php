<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

// Laravel Fortify typical routes (e.g. /login, /register, /forgot-password) 
// are registered automatically after installing Fortify in config/fortify.php
// We add a 'dashboard' route for our logged-in users, plus any other pages.

Route::middleware(['auth', 'verified'])->group(function() {

    // The main route: /dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])
         ->name('dashboard');

    // We remove /awesome, so the calculator is now inside the dashboard Blade.
});