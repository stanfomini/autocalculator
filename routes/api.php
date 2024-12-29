<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AwesomeCalcApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Our CRUD routes for the "awesome" calculators.
|
*/

Route::apiResource('awesome', AwesomeCalcApiController::class);
