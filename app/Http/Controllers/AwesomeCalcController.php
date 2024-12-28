<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AwesomeCalc;

class AwesomeCalcController extends Controller
{
    // Return a new SPA-like Blade that has tabs:
    // 1) Calculator form
    // 2) List of existing calculators
    public function spaIndex()
    {
        return view('awesome.index');
    }

    // Fallback if user visits /awesome/{id} in a browser, show minimal details or redirect
    public function show(AwesomeCalc $awesome)
    {
        // Could just return a blade with the data or redirect to the SPA
        return view('awesome.show', ['calc' => $awesome]);
    }
}