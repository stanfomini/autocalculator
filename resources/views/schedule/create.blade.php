<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Create Schedule</title>
</head>
<body>
    <h1>Create (Non-SPA Fallback)</h1>
    <form method="POST" action="/testing1">
        @csrf
        <p>
            <label>First Name:</label>
            <input type="text" name="first_name" required>
        </p>
        <p>
            <label>Last Name:</label>
            <input type="text" name="last_name" required>
        </p>
        <p>
            <label>Phone:</label>
            <input type="text" name="phone" required>
        </p>
        <p>
            <label>Datetime:</label>
            <input type="datetime-local" name="scheduled_at" required>
        </p>
        <button type="submit">Save</button>
    </form>
</body>
</html>