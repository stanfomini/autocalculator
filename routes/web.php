<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestItemController;

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

// Existing routes...

// New route for /test
Route::resource('/test', TestItemController::class)->only(['index', 'store']);