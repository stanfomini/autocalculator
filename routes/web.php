<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AwesomeCalcController;

/**
 * Basic route to serve the SPA blade at /awesome.
 * We won't do resource routes here, let's keep the full CRUD in /api/awesome
 */
Route::get('/awesome', [AwesomeCalcController::class, 'spaIndex'])
     ->name('awesome.spa');