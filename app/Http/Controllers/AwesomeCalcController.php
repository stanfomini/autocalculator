<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AwesomeCalcController extends Controller
{
    public function spaIndex()
    {
        // Returns the main SPA blade
        return view('awesome.index');
    }
}