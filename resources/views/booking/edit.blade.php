<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Edit Booking</title>
</head>
<body>
    <h1>Edit Booking (Non-SPA Fallback)</h1>
    <form method="POST" action="{{ route('booking.update', $booking->id) }}">
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
            <label>Date &amp; Time</label>
            <input type="datetime-local" name="booking_datetime"
                   value="{{ \Carbon\Carbon::parse($booking->booking_datetime)->format('Y-m-d\TH:i') }}"
                   required>
        </p>
        <button type="submit">Update</button>
    </form>
</body>
</html>