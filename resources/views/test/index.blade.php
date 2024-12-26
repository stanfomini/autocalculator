<!DOCTYPE html>
<html>
<head>
    <title>Test Items</title>
</head>
<body>
    <h1>Test Items</h1>
    <form method="POST" action="{{ route('test.store') }}">
        @csrf
        <label>Enter a message:
            <input type="text" name="message" />
        </label>
        <button type="submit">hey shawn</button>
    </form>

    <h2>Current Messages:</h2>
    <ul>
        @foreach($items as $item)
            <li>{{ $item->message }}</li>
        @endforeach
    </ul>
</body>
</html>