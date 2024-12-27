<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BlogApiController;

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

Route::apiResource('/blogs', BlogApiController::class);