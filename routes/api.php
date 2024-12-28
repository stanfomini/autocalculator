<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AwesomeCalcApiController;

// New API resource for storing/leasing calculators
Route::apiResource('awesome', AwesomeCalcApiController::class);