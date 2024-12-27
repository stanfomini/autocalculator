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

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'phone' => 'required|string|max:30',
            'appointment_datetime' => 'required|date_format:Y-m-d\TH:i', // matches the HTML datetime-local format
        ]);

        Appointment::create($request->only('first_name', 'last_name', 'phone', 'appointment_datetime'));

        // Optionally broadcast event here (e.g., AppointmentCreated)

        return response()->json([
            'status' => 'success',
        ]);
    }
}