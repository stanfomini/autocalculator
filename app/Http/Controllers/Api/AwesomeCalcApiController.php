<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AwesomeCalc;
use Illuminate\Support\Facades\Auth;

/**
 * This controller restricts each user to their own calculators
 */
class AwesomeCalcApiController extends Controller
{
    public function index()
    {
        // only the authenticated user's records
        return AwesomeCalc::where('user_id', Auth::id())->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'calc_type' => 'in:lease,financing,cash',
            'vehicle_price' => 'numeric',
        ]);
        $calcData = $request->all();
        // ensure user_id is set to current user
        $calcData['user_id'] = Auth::id();
        $calc = AwesomeCalc::create($calcData);
        return response()->json($calc, 201);
    }

    public function show(AwesomeCalc $awesome)
    {
        // ensure the user is authorized to view this record
        if($awesome->user_id !== Auth::id()){
            abort(403, "Unauthorized");
        }
        return $awesome;
    }

    public function update(Request $request, AwesomeCalc $awesome)
    {
        // user must own the record
        if($awesome->user_id !== Auth::id()){
            abort(403, "Unauthorized");
        }
        $validated = $request->validate([
            'calc_type' => 'in:lease,financing,cash',
            'vehicle_price' => 'numeric',
        ]);
        $awesome->update($request->all());
        return $awesome;
    }

    public function destroy(AwesomeCalc $awesome)
    {
        // user must own the record
        if($awesome->user_id !== Auth::id()){
            abort(403, "Unauthorized");
        }
        $awesome->delete();
        return response()->json(['status' => 'deleted']);
    }
}