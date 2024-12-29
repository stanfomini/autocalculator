<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AwesomeCalcController extends Controller
{
    public function spaIndex()
    {
        // Return the main Blade with top nav + calculator + saved list
        return view('awesome.index');
    }
}