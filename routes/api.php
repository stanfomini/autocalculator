<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AwesomeCalcApiController;

Route::apiResource('awesome', AwesomeCalcApiController::class);
