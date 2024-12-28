<?php

namespace App\Http\Controllers;

use App\Models\YesTest;
use Illuminate\Http\Request;

class YesTestController extends Controller
{
    public function index()
    {
        // Return the SPA-like Blade
        return view('yestest.index');
    }

    public function create()
    {
        // Non-SPA fallback
        return view('yestest.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name'   => 'required|string|max:100',
            'last_name'    => 'required|string|max:100',
            'phone'        => 'required|string|max:30',
            'scheduled_at' => 'required|date_format:Y-m-d\TH:i',
        ]);

        $test = YesTest::create($request->only([
            'first_name',
            'last_name',
            'phone',
            'scheduled_at',
        ]));

        return response()->json([
            'status' => 'success',
            'record' => $test,
        ]);
    }

    public function show(YesTest $yestest)
    {
        return view('yestest.show', ['record' => $yestest]);
    }

    public function edit(YesTest $yestest)
    {
        return view('yestest.edit', ['record' => $yestest]);
    }

    public function update(Request $request, YesTest $yestest)
    {
        $request->validate([
            'first_name'   => 'required|string|max:100',
            'last_name'    => 'required|string|max:100',
            'phone'        => 'required|string|max:30',
            'scheduled_at' => 'required|date_format:Y-m-d\TH:i',
        ]);

        $yestest->update($request->only([
            'first_name',
            'last_name',
            'phone',
            'scheduled_at',
        ]));

        return response()->json([
            'status' => 'success',
            'record' => $yestest->fresh(),
        ]);
    }

    public function destroy(YesTest $yestest)
    {
        $yestest->delete();

        return response()->json([
            'status' => 'deleted',
        ]);
    }
}