<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    // Show the single-page view containing the form & list
    public function index()
    {
        // You could also return appointments here for server rendering,
        // but we'll rely on SSE for live updates.
        return view('schedule.index');
    }

    // Not strictly needed for SPA, but you can still have a create page
    public function create()
    {
        return view('schedule.create');
    }

    // Store a new appointment (called via AJAX fetch from the SPA form)
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name'  => 'required|string|max:100',
            'phone'      => 'required|string|max:30',
            'appointment_datetime' => 'required|date_format:Y-m-d\TH:i',
        ]);

        $appt = Appointment::create($request->only([
            'first_name',
            'last_name',
            'phone',
            'appointment_datetime',
        ]));

        // SSE watchers will see this new record on next cycle
        return response()->json([
            'status' => 'success',
            'appointment' => $appt,
        ]);
    }

    // Optional: show details for a single appointment
    public function show(Appointment $schedule)
    {
        return view('schedule.show', compact('schedule'));
    }

    // Optional: page for editing
    public function edit(Appointment $schedule)
    {
        return view('schedule.edit', compact('schedule'));
    }

    // Update an existing appointment
    public function update(Request $request, Appointment $schedule)
    {
        $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name'  => 'required|string|max:100',
            'phone'      => 'required|string|max:30',
            'appointment_datetime' => 'required|date_format:Y-m-d\TH:i',
        ]);

        $schedule->update($request->only([
            'first_name',
            'last_name',
            'phone',
            'appointment_datetime',
        ]));

        return response()->json([
            'status' => 'success',
            'appointment' => $schedule->fresh(),
        ]);
    }

    // Delete an appointment
    public function destroy(Appointment $schedule)
    {
        $schedule->delete();

        return response()->json(['status' => 'deleted']);
    }
}