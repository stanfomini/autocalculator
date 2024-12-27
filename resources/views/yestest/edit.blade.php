<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Edit (Non-SPA Fallback)</title>
</head>
<body>
    <h1>Edit Appointment</h1>
    <form action="/yestest/{{ $record->id }}" method="POST">
        @csrf
        @method('PUT')
        <p>
            <label>First Name</label>
            <input type="text" name="first_name" value="{{ $record->first_name }}" required>
        </p>
        <p>
            <label>Last Name</label>
            <input type="text" name="last_name" value="{{ $record->last_name }}" required>
        </p>
        <p>
            <label>Phone</label>
            <input type="text" name="phone" value="{{ $record->phone }}" required>
        </p>
        <p>
            <label>Date & Time</label>
            <input type="datetime-local"
                   name="scheduled_at"
                   value="{{ \Carbon\Carbon::parse($record->scheduled_at)->format('Y-m-d\TH:i') }}"
                   required>
        </p>
        <button type="submit">Update</button>
    </form>
</body>
</html>