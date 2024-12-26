<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <!-- Viewport ensures the page isn't zoomed out or blurry on mobile -->
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
  <title>Fleet Ownership Cost Analyzer</title>
  <!-- Include a manifest for PWA -->
  <link rel="manifest" href="manifest.json">
  <meta name="theme-color" content="#ffffff">

  <style>
    body {
      margin: 0;
      font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen, Ubuntu, Cantarell, "Open Sans", "Helvetica Neue", sans-serif;
      background: #f2f2f7;
      color: #333;
      font-size: 16px;
    }

    .app-container {
      display: flex;
      flex-direction: column;
      height: 100vh;
      overflow: auto;
    }

    .app-header {
      background: #ffffff;
      border-bottom: 1px solid #ccc;
      padding: 1rem;
      font-size: 1.25rem;
      font-weight: bold;
      text-align: center;
    }

    .content {
      flex: 1;
      display: flex;
      flex-direction: column;
      padding: 1rem;
      gap: 1rem;
    }

    .card {
      background: #fff;
      border-radius: 12px;
      padding: 1rem;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
      display: flex;
      flex-direction: column;
      gap: 1rem;
    }

    .tab-bar {
      display: flex;
      gap: 0.5rem;
      justify-content: center;
    }

    .tab {
      padding: 0.5rem 1rem;
      cursor: pointer;
      border-radius: 6px;
      background: #eee;
      font-size: 1rem;
    }

    .tab.active {
      background: #007aff;
      color: #fff;
      font-weight: bold;
    }

    label {
      display: block;
      font-size: 1rem;
      margin-bottom: 0.5rem;
      font-weight: 500;
    }

    input[type="number"], input[type="text"], input[type="checkbox"] {
      transform: scale(1);
      font-size: 16px;
    }

    input[type="number"], input[type="text"] {
      width: 100%;
      padding: 0.75rem;
      font-size: 1rem;
      border: 1px solid #ccc;
      border-radius: 6px;
    }

    .checkbox-label {
      display: flex;
      align-items: center;
      font-size: 1rem;
      gap: 0.5rem;
    }

    .small-label {
      font-weight: 500;
      font-size: 0.9rem;
    }

    .flex-row {
      display: flex;
      gap: 1rem;
      align-items: center;
      flex-wrap: wrap;
    }

    .results-section .cost-indicator {
      padding: 0.5rem;
      border-radius: 6px;
      display: inline-block;
      margin-bottom: 1rem;
      font-weight: bold;
    }

    .green { background-color: #4caf50; color: white; }
    .yellow { background-color: #ffeb3b; color: black; }
    .red { background-color: #f44336; color: white; }

    .results-section h2 {
      font-size: 1.25rem;
      margin-bottom: 1rem;
      font-weight: bold;
    }

    .results-section .detail {
      margin-bottom: 0.5rem;
    }
  </style>
  <script>
    // [Calculator script remains unchanged...]
    // For brevity, omitted the existing code you provided
  </script>
</head>
<body>
  <div class="app-container">
    <div class="app-header">
      Fleet Ownership Cost Analyzer
      <!-- Added a link to the new /test route -->
      <p><a href="/test" style="font-size: 0.9rem; color: blue;">Go to Test Feature</a></p>
    </div>
    <div class="content">
      <!-- Existing content as provided... -->
      <p>Your existing calculator content remains here.</p>
    </div>
  </div>
</body>
</html>