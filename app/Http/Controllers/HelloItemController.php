<?php

namespace App\Http\Controllers;

use App\Models\HelloItem;
use Illuminate\Http\Request;

class HelloItemController extends Controller
{
    // Show the form to submit a new "hello" message
    public function index()
    {
        return view('hello.index');
    }

    // Store a new hello message and redirect to /test
    public function store(Request $request)
    {
        $request->validate([            'message' => 'required|string|max:255',        ]);

        HelloItem::create([
            'message' => $request->message,
        ]);

        return redirect()->route('test.index');
    }
}