<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Show Calculator</title>
</head>
<body style="background-color: #f2f2f2; color:#000; font-family:sans-serif;">
  <h1>AwesomeCalc #{{ $calc->id }}</h1>
  <p>Type: {{ $calc->calc_type }}</p>
  <p>Vehicle Price: {{ $calc->vehicle_price }}</p>
  <p>Rebates & Discounts: {{ $calc->rebates_and_discounts }}</p>
  <!-- etc... -->
</body>
</html>