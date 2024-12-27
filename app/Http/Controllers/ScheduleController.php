<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\Request;

/**
 * Resourceful controller for /testing1
 * This handles CRUD for schedules
 */
class ScheduleController extends Controller
{
    // Show the single-page view
    public function index()
    {
        return view('schedule.index');
    }

    // Non-SPA fallback if you want a /testing1/create page
    public function create()
    {
        return view('schedule.create');
    }

    // Actually store the new record from the "Book Now" button
    public function store(Request $request)
    {
        $request->validate([
            'first_name'   => 'required|string|max:100',
            'last_name'    => 'required|string|max:100',
            'phone'        => 'required|string|max:30',
            'scheduled_at' => 'required|date_format:Y-m-d\TH:i',
        ]);

        $sched = Schedule::create($request->only([
            'first_name',
            'last_name',
            'phone',
            'scheduled_at',
        ]));

        // SSE watchers will see new record on next refresh
        return response()->json([
            'status' => 'success',
            'record' => $sched,
        ]);
    }

    // Non-SPA fallback to show a record
    public function show(Schedule $testing1)
    {
        return view('schedule.show', ['sched' => $testing1]);
    }

    // Non-SPA fallback to edit
    public function edit(Schedule $testing1)
    {
        return view('schedule.edit', ['sched' => $testing1]);
    }

    // Update existing record
    public function update(Request $request, Schedule $testing1)
    {
        $request->validate([
            'first_name'   => 'required|string|max:100',
            'last_name'    => 'required|string|max:100',
            'phone'        => 'required|string|max:30',
            'scheduled_at' => 'required|date_format:Y-m-d\TH:i',
        ]);

        $testing1->update($request->only([
            'first_name',
            'last_name',
            'phone',
            'scheduled_at',
        ]));

        return response()->json([
            'status' => 'success',
            'record' => $testing1->fresh(),
        ]);
    }

    // Delete a record
    public function destroy(Schedule $testing1)
    {
        $testing1->delete();

        return response()->json([
            'status' => 'deleted',
        ]);
    }
}