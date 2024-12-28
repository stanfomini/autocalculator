<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Awesome Calculator Show</title>
</head>
<body style="background-color: #222; color: #ccc; font-family: sans-serif;">
  <h1>Awesome Calculator #{{ $calc->id }}</h1>
  <p>Type: {{ $calc->calc_type }}</p>
  <p>Vehicle Price: {{ $calc->vehicle_price }}</p>
  <p>Rebates & Discounts: {{ $calc->rebates_and_discounts }}</p>
  <p>Down Payment: {{ $calc->down_payment }}</p>
  <!-- etc. You can list all relevant fields here -->
</body>
</html>