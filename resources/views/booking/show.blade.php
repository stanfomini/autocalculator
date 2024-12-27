<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Show Booking</title>
</head>
<body>
    <h1>Booking Details (Non-SPA Fallback)</h1>
    <p>ID: {{ $booking->id }}</p>
    <p>Name: {{ $booking->first_name }} {{ $booking->last_name }}</p>
    <p>Phone: {{ $booking->phone }}</p>
    <p>Date/Time: {{ $booking->booking_datetime }}</p>
</body>
</html>