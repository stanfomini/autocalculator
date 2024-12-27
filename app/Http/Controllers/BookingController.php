<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;

/**
 * A resource controller for the new ?bookings? table.
 * The route will be /booking (Resourceful).
 */
class BookingController extends Controller
{
    public function index()
    {
        // Show the SPA interface
        return view('booking.index');
    }

    // If you want a separate /booking/create page (non-SPA fallback)
    public function create()
    {
        return view('booking.create');
    }

    // Store a new booking (invoked by the ?Book Now? button)
    public function store(Request $request)
    {
        $request->validate([
            'first_name'         => 'required|string|max:100',
            'last_name'          => 'required|string|max:100',
            'phone'              => 'required|string|max:30',
            'booking_datetime'   => 'required|date_format:Y-m-d\TH:i',
        ]);

        $booking = Booking::create($request->only([
            'first_name',
            'last_name',
            'phone',
            'booking_datetime',
        ]));

        // SSE watchers will see this new record
        return response()->json([
            'status'  => 'success',
            'booking' => $booking,
        ]);
    }

    // Non-SPA fallback for showing a single booking
    public function show(Booking $booking)
    {
        return view('booking.show', compact('booking'));
    }

    // Non-SPA fallback for editing
    public function edit(Booking $booking)
    {
        return view('booking.edit', compact('booking'));
    }

    // Update a booking
    public function update(Request $request, Booking $booking)
    {
        $request->validate([
            'first_name'         => 'required|string|max:100',
            'last_name'          => 'required|string|max:100',
            'phone'              => 'required|string|max:30',
            'booking_datetime'   => 'required|date_format:Y-m-d\TH:i',
        ]);

        $booking->update($request->only([
            'first_name',
            'last_name',
            'phone',
            'booking_datetime',
        ]));

        return response()->json([
            'status'  => 'success',
            'booking' => $booking->fresh(),
        ]);
    }

    // Delete a booking
    public function destroy(Booking $booking)
    {
        $booking->delete();

        return response()->json([
            'status' => 'deleted',
        ]);
    }
}