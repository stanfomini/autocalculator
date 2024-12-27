<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Create (Non-SPA)</title>
</head>
<body>
    <h1>Create Form (fallback)</h1>
    <form action="/testing1" method="POST">
        @csrf
        <p>
            <label>First Name</label>
            <input type="text" name="first_name" required>
        </p>
        <p>
            <label>Last Name</label>
            <input type="text" name="last_name" required>
        </p>
        <p>
            <label>Phone</label>
            <input type="text" name="phone" required>
        </p>
        <p>
            <label>DateTime</label>
            <input type="datetime-local" name="scheduled_at" required>
        </p>
        <button type="submit">Save</button>
    </form>
</body>
</html>