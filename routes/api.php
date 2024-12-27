<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Appointment;

// Return JSON list of appointments for real-time updates
Route::get('/appointments', function () {
    $appointments = Appointment::select('*')->get()->map(function($appt) {
        // Add an "is_new" flag if created < 10 minutes ago
        $appt->is_new = $appt->created_at && $appt->created_at->gt(\Carbon\Carbon::now()->subMinutes(10));
        return $appt;
    });

    return response()->json($appointments);
});

// Default user route snippet
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');