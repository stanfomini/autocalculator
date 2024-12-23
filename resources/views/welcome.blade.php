<!DOCTYPE html> <html lang="{{ str_replace('_', '-', app()->getLocale()) }}"> <head> <meta charset="utf-8"> <meta name="viewport" content="width=device-width, initial-scale=1"> <title>Automotive Calculator</title> <link rel="preconnect" href="https://fonts.bunny.net"> <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" /> <style> /* Simple Tailwind base */ *, ::after, ::before { box-sizing: border-box; margin: 0; padding: 0; } body, html { font-family: Figtree, ui-sans-serif, system-ui, sans-serif; min-height: 100vh; background: #f9fafb; color: #333; } header, main, footer { padding: 1rem 1.5rem; } .navbar { display: flex; justify-content: space-between; align-items: center; } .nav-links { display: flex; gap: 1rem; } .content { max-width: 800px; margin: 3rem auto; text-align: center; } .content h1 { font-size: 2.25rem; margin-bottom: 1.5rem; font-weight: 600; } .content p { margin-bottom: 1rem; line-height: 1.6; } footer { text-align: center; margin-top: 3rem; font-size: 0.875rem; } .btn { border: 1px solid transparent; padding: 0.5rem 1rem; border-radius: 0.25rem; transition: background-color 0.2s; text-decoration: none; color: #333; } .btn:hover { background-color: #e2e8f0; } </style> </head> <body> <header class="navbar"> <h2 style="font-size:1.5rem; font-weight:bold;">Automotive Calculator</h2> @if (Route::has('login')) <nav class="nav-links"> @auth <a href="{{ url('/dashboard') }}" class="btn">Dashboard</a> @else <a href="{{ route('login') }}" class="btn">Log in</a> @if (Route::has('register')) <a href="{{ route('register') }}" class="btn">Register</a> @endif @endauth </nav> @endif </header>
    <main class="content">
    <h1>Empower Your Decisions with Real Numbers</h1>
    <p>
        Discover how powerful it can be to harness accurate calculations before making major automotive decisions.
        Our Automotive Calculator helps you see the full picture—fuel costs, loan comparisons, and overall
        budgeting—so you're never left in the dark.
    </p>
    <p>
        With just a few inputs, you'll get the math that can guide your purchasing or financing strategies,
        helping you save money and avoid costly mistakes. When the numbers are clear, you'll feel more confident
        about every move you make.
    </p>
</main>

<footer>
    <p>Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})</p>
</footer>
</body> 
</html>
