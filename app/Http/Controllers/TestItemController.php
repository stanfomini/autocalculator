<?php

namespace App\Http\Controllers;

use App\Models\TestItem;
use Illuminate\Http\Request;

class TestItemController extends Controller
{
    // Display a listing of items at /test
    public function index()
    {
        $items = TestItem::latest()->get();
        return view('test.index', compact('items'));
    }

    // Store a new item from the form
    public function store(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:255'
        ]);

        TestItem::create([
            'message' => $request->message,
        ]);

        return redirect()->route('test.index');
    }
}