<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Create Booking</title>
</head>
<body>
    <h1>Create Booking (Non-SPA Fallback)</h1>
    <form method="POST" action="{{ route('booking.store') }}">
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
            <label>Date &amp; Time</label>
            <input type="datetime-local" name="booking_datetime" required>
        </p>
        <button type="submit">Save</button>
    </form>
</body>
</html>