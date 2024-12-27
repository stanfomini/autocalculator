<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\Request;

/**
 * Resourceful controller for the "schedules" table, using route /testing1.
 */
class ScheduleController extends Controller
{
    public function index()
    {
        // Return the single-page scheduler
        return view('schedule.index');
    }

    public function create()
    {
        // Non-SPA fallback
        return view('schedule.create');
    }

    public function store(Request $request)
    {
        // Validate and store the record
        $request->validate([
            'first_name'   => 'required|string|max:100',
            'last_name'    => 'required|string|max:100',
            'phone'        => 'required|string|max:30',
            'scheduled_at' => 'required|date_format:Y-m-d\TH:i',
        ]);

        // Use the full $request->all() instead of manually picking fields
        $sched = Schedule::create($request->all());


        return response()->json([
            'status' => 'success',
            'record' => $sched,
        ]);
    }

    // For non-SPA usage, show a single record
    public function show(Schedule $testing1)
    {
        return view('schedule.show', ['sched' => $testing1]);
    }

    public function edit(Schedule $testing1)
    {
        return view('schedule.edit', ['sched' => $testing1]);
    }

    public function update(Request $request, Schedule $testing1)
    {
        $request->validate([
            'first_name'   => 'required|string|max:100',
            'last_name'    => 'required|string|max:100',
            'phone'        => 'required|string|max:30',
            'scheduled_at' => 'required|date_format:Y-m-d\TH:i',
        ]);

        // Use the full $request->all() for updating instead of manual picking.
        $testing1->update($request->all());

        return response()->json([
            'status' => 'success',
            'record' => $testing1->fresh(),
        ]);
    }

    public function destroy(Schedule $testing1)
    {
        $testing1->delete();

        return response()->json([
            'status' => 'deleted',
        ]);
    }
}