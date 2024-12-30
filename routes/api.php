<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AwesomeCalcApiController;

// We protect these routes with 'auth:sanctum' or similar, so only logged-in users
Route::middleware('auth:sanctum')->group(function(){

    Route::apiResource('awesome', AwesomeCalcApiController::class);

});