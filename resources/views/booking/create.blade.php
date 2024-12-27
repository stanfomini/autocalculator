<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Create Booking</title>
</head>
<body>
    <h1>Create Appointment (non-SPA fallback)</h1>
    <form action="{{ route('booking.store') }}" method="POST">
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
            <label>Appointment Date & Time</label>
            <input type="datetime-local" name="appointment_datetime" required>
        </p>
        <button type="submit">Save</button>
    </form>
</body>
</html>