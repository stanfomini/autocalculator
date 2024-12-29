<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AwesomeCalcController;

Route::get('/awesome', [AwesomeCalcController::class, 'spaIndex'])
     ->name('awesome.spa');
