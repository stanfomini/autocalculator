<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Create Blog</title>
</head>
<body>
    <h1>Create (Non-SPA Fallback)</h1>
    <form action="/blog" method="POST">
        @csrf
        <p>
            <label>Title:</label>
            <input type="text" name="title" required>
        </p>
        <p>
            <label>Content:</label>
            <textarea name="content" required></textarea>
        </p>
        <button type="submit">Save</button>
    </form>
</body>
</html>