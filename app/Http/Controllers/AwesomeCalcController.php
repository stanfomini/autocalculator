<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AwesomeCalcController extends Controller
{
    public function spaIndex()
    {
        return view('awesome.index');
    }
}