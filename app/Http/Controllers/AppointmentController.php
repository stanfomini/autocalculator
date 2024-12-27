<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index()
    {
        return view('schedule.index');
    }

    // Store new appointment (via JSON Fetch from front end)
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'phone' => 'required|string|max:30',
            'appointment_datetime' => 'required|date_format:Y-m-d\TH:i', // matches HTML datetime-local
        ]);

        $appt = Appointment::create($request->only(
            'first_name',
            'last_name',
            'phone',
            'appointment_datetime'
        ));

        // Optionally broadcast an event here for WebSockets,
        // but SSE approach will pick up newly stored records
        // automatically whenever the SSE stream reloads them.

        return response()->json([
            'status' => 'success',
            'appointment' => $appt,
        ]);
    }
}