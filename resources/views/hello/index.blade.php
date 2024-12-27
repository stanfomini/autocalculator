<!DOCTYPE html>
<html>
<head>
    <title>Hello Feature</title>
</head>
<body>
    <h1>Say Hello</h1>
    <form method="POST" action="{{ route('hello.store') }}">
        @csrf
        <label>Enter a message:
            <input type="text" name="message" placeholder="Type something..." />
        </label>
        <button type="submit">hey brandon</button>
    </form>
</body>
</html>