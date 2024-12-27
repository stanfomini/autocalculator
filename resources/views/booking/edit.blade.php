<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Edit Booking</title>
</head>
<body>
    <h1>Edit Appointment (non-SPA fallback)</h1>
    <form action="{{ route('booking.update', $booking->id) }}" method="POST">
        @csrf
        @method('PUT')
        <p>
            <label>First Name</label>
            <input type="text" name="first_name" value="{{ $booking->first_name }}" required>
        </p>
        <p>
            <label>Last Name</label>
            <input type="text" name="last_name" value="{{ $booking->last_name }}" required>
        </p>
        <p>
            <label>Phone</label>
            <input type="text" name="phone" value="{{ $booking->phone }}" required>
        </p>
        <p>
            <label>Appointment Date & Time</label>
            <input type="datetime-local" name="appointment_datetime"
                   value="{{ \Carbon\Carbon::parse($booking->appointment_datetime)->format('Y-m-d\TH:i') }}"
                   required>
        </p>
        <button type="submit">Update Appointment</button>
    </form>
</body>
</html>