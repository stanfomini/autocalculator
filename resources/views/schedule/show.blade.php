<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Show Appointment</title>
</head>
<body>
    <h1>Appointment Details (non-SPA fallback)</h1>
    <p>ID: {{ $schedule->id }}</p>
    <p>Name: {{ $schedule->first_name }} {{ $schedule->last_name }}</p>
    <p>Phone: {{ $schedule->phone }}</p>
    <p>Datetime: {{ $schedule->appointment_datetime }}</p>
</body>
</html>