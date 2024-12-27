<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Show Schedule</title>
</head>
<body>
    <h1>Schedule Record (Non-SPA Fallback)</h1>
    <p>ID: {{ $sched->id }}</p>
    <p>Name: {{ $sched->first_name }} {{ $sched->last_name }}</p>
    <p>Phone: {{ $sched->phone }}</p>
    <p>Datetime: {{ $sched->scheduled_at }}</p>
</body>
</html>