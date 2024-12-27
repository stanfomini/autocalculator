<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestItemController;
use App\Http\Controllers\HelloItemController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\ScheduleSseController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\BlogSseController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

Route::resource('/test', TestItemController::class)->only(['index', 'store']);
Route::resource('/hello', HelloItemController::class)->only(['index', 'store']);

// The new scheduling resource at /testing1
Route::resource('/testing1', ScheduleController::class)
    ->parameters(['testing1' => 'testing1']);

// SSE route for real-time
Route::get('/testing1/sse', [ScheduleSseController::class, 'stream'])->name('testing1.sse');

// Blog Resource
Route::resource('/blog', BlogController::class);
// Blog SSE route
Route::get('/blog/sse', [BlogSseController::class, 'stream'])->name('blog.sse');