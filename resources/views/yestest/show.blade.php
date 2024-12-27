<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Show (Non-SPA Fallback)</title>
</head>
<body>
    <h1>Show Appointment</h1>
    <p>ID: {{ $record->id }}</p>
    <p>Name: {{ $record->first_name }} {{ $record->last_name }}</p>
    <p>Phone: {{ $record->phone }}</p>
    <p>Datetime: {{ $record->scheduled_at }}</p>
</body>
</html>