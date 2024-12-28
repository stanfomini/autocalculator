<?php

namespace App\Http\Controllers;

use App\Models\YesTest;
use Illuminate\Http\Request;

class YesTestController extends Controller
{
    /**
     * Display the main SPA-like page for all CRUD actions.
     */
    public function index()
    {
        // Return the single Blade that handles all CRUD via JavaScript.
        return view('yestest.index');
    }

    /**
     * Redirect create to index, because the form is on the same page as the listing.
     */
    public function create()
    {
        return redirect()->route('yestest.index');
    }

    /**
     * Store a newly created record (called by JavaScript fetch from index blade).
     */
    public function store(Request $request)
    {
        $request->validate([
            'first_name'   => 'required|string|max:100',
            'last_name'    => 'required|string|max:100',
            'phone'        => 'required|string|max:30',
            'scheduled_at' => 'required|date_format:Y-m-d\\TH:i',
        ]);

        $record = YesTest::create($request->only([
            'first_name',
            'last_name',
            'phone',
            'scheduled_at',
        ]));

        // Return JSON so the SPA can handle success without a full page reload
        return response()->json([
            'status' => 'success',
            'record' => $record,
        ]);
    }

    public function show(YesTest $yestest)
    {
        // For a fallback non-SPA view if needed
        return view('yestest.show', ['record' => $yestest]);
    }

    public function edit(YesTest $yestest)
    {
        // Fallback non-SPA editing
        return view('yestest.edit', ['record' => $yestest]);
    }

    /**
     * Update an existing record via JavaScript fetch from index blade.
     */
    public function update(Request $request, YesTest $yestest)
    {
        $request->validate([
            'first_name'   => 'required|string|max:100',
            'last_name'    => 'required|string|max:100',
            'phone'        => 'required|string|max:30',
            'scheduled_at' => 'required|date_format:Y-m-d\\TH:i',
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

    /**
     * Delete the specified record via JavaScript fetch from index blade.
     */
    public function destroy(YesTest $yestest)
    {
        $yestest->delete();

        return response()->json([
            'status' => 'deleted',
        ]);
    }
}