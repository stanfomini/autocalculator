<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AwesomeCalc;

class AwesomeCalcApiController extends Controller
{
    public function index()
    {
        return AwesomeCalc::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'calc_type' => 'in:lease,financing,cash',
            'vehicle_price' => 'numeric',
            'rebates_and_discounts' => 'numeric',
            // etc. Add more validation as needed
        ]);

        $calc = AwesomeCalc::create($validated);
        return response()->json($calc, 201);
    }

    public function show(AwesomeCalc $awesome)
    {
        return $awesome;
    }

    public function update(Request $request, AwesomeCalc $awesome)
    {
        $validated = $request->validate([
            'calc_type' => 'in:lease,financing,cash',
            'vehicle_price' => 'numeric',
            // etc
        ]);

        $awesome->update($validated);
        return $awesome;
    }

    public function destroy(AwesomeCalc $awesome)
    {
        $awesome->delete();
        return response()->json(['status' => 'deleted'], 200);
    }
}