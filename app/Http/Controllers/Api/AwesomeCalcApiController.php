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
        // Minimal validation for demonstration
        $validated = $request->validate([
            'calc_type' => 'in:lease,financing,cash',
            'vehicle_price' => 'numeric',
        ]);
        $calc = AwesomeCalc::create($request->all());
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
        ]);
        $awesome->update($request->all());
        return $awesome;
    }

    public function destroy(AwesomeCalc $awesome)
    {
        $awesome->delete();
        return response()->json(['status' => 'deleted']);
    }
}