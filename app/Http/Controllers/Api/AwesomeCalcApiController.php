<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AwesomeCalc;

/**
 * This controller stores/updates all three scenarios (lease, financing, cash)
 * in the same "awesome_calcs" table. We simply pass the correct fields from
 * the front-end. For future expansions (e.g., user ownership), we'll add more
 * columns or relationships. 
 */
class AwesomeCalcApiController extends Controller
{
    public function index()
    {
        // Return all calculations. In the future, we'd likely filter by user_id.
        return AwesomeCalc::all();
    }

    public function store(Request $request)
    {
        // Basic validation: we ensure calc_type is lease/financing/cash, vehicle_price numeric
        $validated = $request->validate([
            'calc_type' => 'in:lease,financing,cash',
            'vehicle_price' => 'numeric',
        ]);
        // Create record
        $calc = AwesomeCalc::create($request->all());
        return response()->json($calc, 201);
    }

    public function show(AwesomeCalc $awesome)
    {
        // Return the single record. 
        return $awesome;
    }

    public function update(Request $request, AwesomeCalc $awesome)
    {
        // We can do similar validation on update
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