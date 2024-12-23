<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Automotive Calculator</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    <style>
        /* Minimal Tailwind-like setup */
        *,::after,::before {
            box-sizing: border-box;
        }
        body,html {
            margin: 0;
            padding: 0;
            font-family: Figtree, ui-sans-serif, system-ui, sans-serif;
            background-color: #f9fafb;
            color: #333;
        }
        header, main, footer {
            padding: 1rem 1.5rem;
        }
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .nav-links {
            display: flex;
            gap: 1rem;
        }
        .hero {
            max-width: 800px;
            margin: 3rem auto;
            text-align: center;
        }
        .hero h1 {
            font-size: 2.25rem;
            margin-bottom: 1.5rem;
            font-weight: 600;
        }
        .hero p {
            margin-bottom: 1rem;
            line-height: 1.6;
        }
        footer {
            text-align: center;
            margin-top: 3rem;
            font-size: 0.875rem;
        }
        .btn {
            display: inline-block;
            padding: 0.5rem 1rem;
            text-decoration: none;
            color: #333;
            border: 1px solid transparent;
            border-radius: 0.25rem;
            transition: background-color 0.2s;
        }
        .btn:hover {
            background-color: #e2e8f0;
        }
    </style>
</head>
<body>
    <header class="navbar">
        <h2 style="font-size:1.5rem; font-weight:bold;">Automotive Calculator</h2>
        @if (Route::has('login'))
            <nav class="nav-links">
                @auth
                    <a href="{{ url('/dashboard') }}" class="btn">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="btn">Log in</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn">Register</a>
                    @endif
                @endauth
            </nav>
        @endif
    </header>

    <main class="hero">
        <h1>Make Decisions With Real Numbers</h1>
        <p>
            Discover the impact of an automotive calculator that actually shows you the math behind the choices you make.
            Whether it's estimating monthly payments, projecting fuel costs, or comparing maintenance fees, concrete numbers
            can make all the difference in saving money.
        </p>
        <p>
            Our calculator cuts through the noise and gives you a clear snapshot of costs, so you can avoid surprises
            and negotiate from a position of strength. Make every automotive decision easier and more cost-effective.
        </p>
    </main>

    <footer>
        <p>Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})</p>
    </footer>
</body>
</html>