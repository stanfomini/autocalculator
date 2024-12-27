<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Show Blog</title>
</head>
<body>
    <h1>Show Blog Record (Non-SPA)</h1>
    <p>ID: {{ $blog->id }}</p>
    <p>Title: {{ $blog->title }} </p>
    <p>Content: {{ $blog->content }}</p>
</body>
</html>