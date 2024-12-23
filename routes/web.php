<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserManagementcontroller;
use App\Http\Livewire\Dashboard;
//use App\Http\Livewire\Customer\Dashboard as CustomerDashboard;
use App\Http\Livewire\AddCustomerForm;


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
// Customer Dashboard Route (No authentication middleware)
//Route::get('/dashboard/{companySlug}/{customerPhone}', CustomerDashboard::class)->name('customer.dashboard');
//

Route::middleware(['auth:sanctum', 'verified'])->get('/add-customer', AddCustomerForm::class)->name('add-customer');




