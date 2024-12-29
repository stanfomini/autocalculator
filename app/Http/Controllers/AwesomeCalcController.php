<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AwesomeCalcController extends Controller
{
    public function spaIndex()
    {
        // Return the main Blade, which has the old calculator UI + a new ?Saved? page
        return view('awesome.index');
    }
}