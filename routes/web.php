<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AwesomeCalcController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| A single route to serve our SPA at /awesome
|
*/

Route::get('/awesome', [AwesomeCalcController::class, 'spaIndex'])
     ->name('awesome.spa');
