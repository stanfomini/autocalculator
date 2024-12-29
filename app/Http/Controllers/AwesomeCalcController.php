<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AwesomeCalcController extends Controller
{
    public function spaIndex()
    {
        // Return the SPA blade at /awesome
        return view('awesome.index');
    }
}