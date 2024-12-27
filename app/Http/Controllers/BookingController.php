<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;

/**
 * Handles CRUD for the existing appointments table, but
 * now accessed via the /booking route (SPA style).
 */
class BookingController extends Controller
{
    // Show the single-page booking interface
    public function index()
    {
        // Return the new booking Blade
        return view('booking.index');
    }

    // Not strictly necessary for an SPA, but included
    public function create()
    {
        return view('booking.create');
    }

    // Store a new appointment record
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name'  => 'required|string|max:100',
            'phone'      => 'required|string|max:30',
            'appointment_datetime' => 'required|date_format:Y-m-d\TH:i',
        ]);

        $appt = Appointment::create($request->only(
            'first_name',
            'last_name',
            'phone',
            'appointment_datetime'
        ));

        // SSE watchers will see the new record
        return response()->json([
            'status' => 'success',
            'appointment' => $appt,
        ]);
    }

    // For non-SPA usage
    public function show(Appointment $booking)
    {
        return view('booking.show', compact('booking'));
    }

    public function edit(Appointment $booking)
    {
        return view('booking.edit', compact('booking'));
    }

    public function update(Request $request, Appointment $booking)
    {
        $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name'  => 'required|string|max:100',
            'phone'      => 'required|string|max:30',
            'appointment_datetime' => 'required|date_format:Y-m-d\TH:i',
        ]);

        $booking->update($request->only([
            'first_name',
            'last_name',
            'phone',
            'appointment_datetime',
        ]));

        return response()->json([
            'status' => 'success',
            'appointment' => $booking->fresh(),
        ]);
    }

    public function destroy(Appointment $booking)
    {
        $booking->delete();

        return response()->json([
            'status' => 'deleted',
        ]);
    }
}