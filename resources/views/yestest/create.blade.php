<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>YesTest Create (Non-SPA Fallback)</title>
</head>
<body>
    <h1>Create Form</h1>
    <form action="/yestest" method="POST">
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
            <label>Date & Time</label>
            <input type="datetime-local" name="scheduled_at" required>
        </p>
        <button type="submit">Save</button>
    </form>
</body>
</html>