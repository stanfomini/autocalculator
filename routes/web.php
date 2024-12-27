<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestItemController;
use App\Http\Controllers\HelloItemController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application.
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Existing route for test
Route::resource('/test', TestItemController::class)->only(['index', 'store']);

// New route for /hello
Route::resource('/hello', HelloItemController::class)->only(['index', 'store']);

Route::get('/dashboard', function () {
    return view('dashboard');
});