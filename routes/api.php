<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// We can remove the old polling approach for /api/appointments
// or keep it if desired. Example below is commented out:

/*
use App\Models\Appointment;

Route::get('/appointments', function () {
    $appointments = Appointment::all();
    return response()->json($appointments);
});
*/

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');