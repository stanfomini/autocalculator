<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index()
    {
        return view('blog.index');
    }

    public function create()
    {
        return view('blog.create');
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
        ]);
    }


    public function show(Blog $blog)
    {
        return view('blog.show', ['blog' => $blog]);
    }

    public function edit(Blog $blog)
    {
        return view('blog.edit', ['blog' => $blog]);
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
        return response()->json([
            'status' => 'deleted',
        ]);
    }
}