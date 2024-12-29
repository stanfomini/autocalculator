<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AwesomeCalcController extends Controller
{
    public function spaIndex()
    {
        // Return the Blade with the old calculator + new "Saved" page
        return view('awesome.index');
    }
}