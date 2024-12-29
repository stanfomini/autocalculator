<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AwesomeCalcController extends Controller
{
    // Return the single-page Blade view at /awesome
    public function spaIndex()
    {
        return view('awesome.index');
    }
}