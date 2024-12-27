<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Show Booking</title>
</head>
<body>
    <h1>Appointment Details (non-SPA fallback)</h1>
    <p>ID: {{ $booking->id }}</p>
    <p>Name: {{ $booking->first_name }} {{ $booking->last_name }}</p>
    <p>Phone: {{ $booking->phone }}</p>
    <p>Datetime: {{ $booking->appointment_datetime }}</p>
</body>
</html>