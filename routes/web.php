<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PasswordController;
use App\Http\Livewire\AddCustomerForm;

// Existing routes...

Route::get('/', function () {
    return view('welcome');
});

Route::get('/password/change', [PasswordController::class, 'showChangePasswordForm'])->name('password.change');
Route::post('/password/change', [PasswordController::class, 'updatePassword']);

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Route::middleware(['auth:sanctum', 'verified'])->get('/add-customer', AddCustomerForm::class)->name('add-customer');

// New calculator route
Route::get('/calculator', function () {
    return view('calculator');
});