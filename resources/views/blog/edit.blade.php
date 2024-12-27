<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Edit Blog</title>
</head>
<body>
    <h1>Edit (Non-SPA)</h1>
    <form method="POST" action="/blog/{{ $blog->id }}">
        @csrf
        @method('PUT')
         <p>
            <label>Title</label>
            <input type="text" name="title" value="{{ $blog->title }}" required>
        </p>
        <p>
            <label>Content</label>
             <textarea name="content" required> {{ $blog->content }} </textarea>
        </p>
        <button type="submit">Update</button>
    </form>
</body>
</html>