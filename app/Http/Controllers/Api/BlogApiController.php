<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;

class BlogApiController extends Controller
{
    public function index()
    {
         return response()->json(Blog::orderBy('id', 'desc')->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'   => 'required|string|max:200',
            'content' => 'required|string',
        ]);

        $blog = Blog::create($request->all());

        return response()->json([
            'status' => 'success',
            'record' => $blog,
        ], 201);
    }

    public function show(Blog $blog)
    {
       return response()->json($blog);
    }

   public function update(Request $request, Blog $blog)
    {
         $request->validate([
            'title'   => 'required|string|max:200',
            'content' => 'required|string',
        ]);
        $blog->update($request->all());
        return response()->json([
            'status' => 'success',
            'record' => $blog->fresh(),
        ]);
    }

    public function destroy(Blog $blog)
    {
        $blog->delete();
        return response()->json(['status' => 'deleted']);
    }
}
