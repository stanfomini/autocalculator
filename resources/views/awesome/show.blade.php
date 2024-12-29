<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Show Calculator</title>
</head>
<body style="background-color:#1e1e2f; color:#eee; font-family:sans-serif;">
  <h1>AwesomeCalc #{{ $calc->id }}</h1>
  <p>Type: {{ $calc->calc_type }}</p>
  <p>Vehicle Price: {{ $calc->vehicle_price }}</p>
  <p>Rebates & Discounts: {{ $calc->rebates_and_discounts }}</p>
</body>
</html>