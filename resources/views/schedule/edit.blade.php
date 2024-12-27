<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Edit Schedule</title>
</head>
<body>
    <h1>Edit (Non-SPA Fallback)</h1>
    <form method="POST" action="/testing1/{{ $sched->id }}">
        @csrf
        @method('PUT')
        <p>
            <label>First Name:</label>
            <input type="text" name="first_name"
                   value="{{ $sched->first_name }}" required>
        </p>
        <p>
            <label>Last Name:</label>
            <input type="text" name="last_name"
                   value="{{ $sched->last_name }}" required>
        </p>
        <p>
            <label>Phone:</label>
            <input type="text" name="phone"
                   value="{{ $sched->phone }}" required>
        </p>
        <p>
            <label>Datetime:</label>
            <input type="datetime-local" name="scheduled_at"
                   value="{{ \Carbon\Carbon::parse($sched->scheduled_at)->format('Y-m-d\TH:i') }}"
                   required>
        </p>
        <button type="submit">Update</button>
    </form>
</body>
</html>