<!DOCTYPE html>
<html>
<head>
    <title>Test Items</title>
</head>
<body>
    <h1>Test Items</h1>
    <p>Below is the list of messages submitted via the /hello route, stored in hello_items.</p>

    <ul>
        @php
            // Load "HelloItem" records. If "HelloItem" not included, we can do so:
            use App\Models\HelloItem;
            $helloItems = HelloItem::latest()->get();
        @endphp

        @foreach($helloItems as $hello)
            <li>{{ $hello->message }}</li>
        @endforeach
    </ul>

    <hr>

    <h2>Existing Test Items</h2>
    <!-- Existing form or logic for test items remains below, e.g.: -->
    <form method="POST" action="{{ route('test.store') }}">
        @csrf
        <label>Enter a test item:
            <input type="text" name="message" />
        </label>
        <button type="submit">Save Test Item</button>
    </form>

    <h3>Current Test Items:</h3>
    @foreach($items as $item)
        <p>{{ $item->message }}</p>
    @endforeach
</body>
</html>