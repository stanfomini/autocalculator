<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * Just a controller that returns the Blade with our calculator code
 * for the user's dashboard at /dashboard. We'll show the same "awesome.index"
 * view, or a new "dashboard.blade.php" that has the same content.
 */
class DashboardController extends Controller
{
    public function index()
    {
        // Return the same view we had, but rename it to "dashboard" if desired
        // For example, resources/views/dashboard.blade.php
        return view('dashboard');
    }
}