<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Edit Appointment</title>
</head>
<body>
    <h1>Edit Appointment (non-SPA fallback)</h1>
    <form action="{{ route('schedule.update', $schedule->id) }}" method="POST">
        @csrf
        @method('PUT')
        <p>
            <label>First Name</label>
            <input type="text" name="first_name" value="{{ $schedule->first_name }}" required>
        </p>
        <p>
            <label>Last Name</label>
            <input type="text" name="last_name" value="{{ $schedule->last_name }}" required>
        </p>
        <p>
            <label>Phone</label>
            <input type="text" name="phone" value="{{ $schedule->phone }}" required>
        </p>
        <p>
            <label>Appointment Date & Time</label>
            <input type="datetime-local" name="appointment_datetime"
                   value="{{ \Carbon\Carbon::parse($schedule->appointment_datetime)->format('Y-m-d\TH:i') }}"
                   required>
        </p>
        <button type="submit">Save Changes</button>
    </form>
</body>
</html>