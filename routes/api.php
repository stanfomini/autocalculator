<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AwesomeCalcApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| CRUD routes for the "awesome" calculators.
|
*/

Route::apiResource('awesome', AwesomeCalcApiController::class);
