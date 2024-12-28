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
     * (Optional) We can remove or redirect the 'create' method 
     * since the form lives in yestest.index.blade.php.
     */
    public function create()
    {
        // Redirect to index so we don't display a separate create page.
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
            'scheduled_at' => 'required|date_format:Y-m-d\TH:i',
        ]);

        $record = YesTest::create($request->only([
            'first_name',
            'last_name',
            'phone',
            'scheduled_at',
        ]));

        return response()->json([
            'status' => 'success',
            'record' => $record,
        ]);
    }

    /**
     * If you want a fallback single-record view, keep show(). 
     * Otherwise, remove or redirect it as well.
     */
    public function show(YesTest $yestest)
    {
        return view('yestest.show', ['record' => $yestest]);
    }

    /**
     * (Optional) We can remove or redirect the 'edit' method 
     * since the form is in yestest.index.blade.php, not a separate page.
     */
    public function edit(YesTest $yestest)
    {
        // Redirect to index or do nothing.
        return redirect()->route('yestest.index');
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