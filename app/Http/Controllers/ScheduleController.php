<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\Request;

/**
 * Resourceful controller for the ?schedules? table at /testing1.
 */
class ScheduleController extends Controller
{
    public function index()
    {
        return view('schedule.index');
    }

    public function create()
    {
        // Non-SPA fallback
        return view('schedule.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name'   => 'required|string|max:100',
            'last_name'    => 'required|string|max:100',
            'phone'        => 'required|string|max:30',
            'scheduled_at' => 'required|date_format:Y-m-d\TH:i',
        ]);

        // Create new schedule record
        $sched = Schedule::create($request->only([
            'first_name',
            'last_name',
            'phone',
            'scheduled_at',
        ]));

        return response()->json([
            'status' => 'success',
            'record' => $sched,
        ]);
    }

    // Non-SPA fallback show
    public function show(Schedule $testing1)
    {
        return view('schedule.show', ['sched' => $testing1]);
    }

    // Non-SPA fallback edit
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

    public function destroy(Schedule $testing1)
    {
        $testing1->delete();

        return response()->json([
            'status' => 'deleted',
        ]);
    }
}