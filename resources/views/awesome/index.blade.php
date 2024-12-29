<!DOCTYPE html>
<html lang="en" x-data="{ tab: 'calc' }">
<head>
  <meta charset="UTF-8">
  <title>Awesome Calculator SPA</title>

  <!-- Alpine for tab switching only -->
  <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

  <!-- Optional CSRF if needed -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  @vite(['resources/css/app.css','resources/js/app.js'])

  <style>
    body {
      margin: 0;
      padding: 1rem;
      background-color: #f2f2f2; /* Light background */
      color: #000; /* Black text */
      font-family: sans-serif;
    }
    .tab-button {
      background-color: #ddd;
      color: #333;
      padding: 0.5rem 1rem;
      margin-right: 0.5rem;
      border-radius: 0.25rem;
      cursor: pointer;
      border: none;
    }
    .tab-button.active {
      background-color: #007aff;
      color: #fff;
    }
    .card {
      background-color: #fff;
      border: 1px solid #ccc;
      border-radius: 8px;
      padding: 1rem;
      margin-bottom: 1rem;
    }
    input, select {
      background-color: #fff;
      border: 1px solid #999;
      color: #000;
      padding: 0.5rem;
      border-radius: 4px;
      width: 100%;
      box-sizing: border-box;
      margin-bottom: 0.5rem;
    }
    .btn-primary {
      background-color: #007aff;
      color: #fff;
      padding: 0.5rem 1rem;
      border-radius: 4px;
      border: none;
      cursor: pointer;
    }
    .btn-primary:hover {
      background-color: #005bb5;
    }
  </style>
</head>
<body onload="initApp()">

  <h1 style="font-size:1.5rem; margin-bottom:1rem;">Awesome Calculator SPA</h1>

  <div style="margin-bottom:1rem;">
    <button class="tab-button" :class="{ 'active': tab==='calc' }" @click="tab='calc'">Calculator</button>
    <button class="tab-button" :class="{ 'active': tab==='list' }" @click="tab='list'">List of Calculators</button>
  </div>

  <!-- Calculator Card -->
  <div class="card" x-show="tab==='calc'">
    <h2 style="font-size:1.25rem; margin-bottom:0.5rem;">Finance/Lease Calculator</h2>
    <form onsubmit="saveCalculator(event)">
      <label>Calculation Type</label>
      <select id="calc_type">
        <option value="lease">Lease</option>
        <option value="financing">Financing</option>
        <option value="cash">Cash</option>
      </select>

      <label>Vehicle Price</label>
      <input type="number" step="0.01" id="vehicle_price">

      <label>Rebates & Discounts</label>
      <input type="number" step="0.01" id="rebates_and_discounts">

      <label>Down Payment</label>
      <input type="number" step="0.01" id="down_payment">

      <label>Term (Months)</label>
      <input type="number" id="term_months" value="36">

      <label>Residual (%)</label>
      <input type="number" step="0.01" id="residual_percent">

      <label>Residual Value ($)</label>
      <input type="number" step="0.01" id="residual_value">

      <label>Money Factor</label>
      <input type="number" step="0.00001" id="money_factor">

      <label>Tax (%)</label>
      <input type="number" step="0.01" id="tax_percent">

      <label>Tax (Total $)</label>
      <input type="number" step="0.01" id="tax_total">

      <label><input type="checkbox" id="capitalize_taxes"> Capitalize Taxes</label>

      <label>Additional Fees ($)</label>
      <input type="number" step="0.01" id="additional_fees">

      <label><input type="checkbox" id="capitalize_fees"> Capitalize Fees</label>

      <label>Yearly Maintenance ($)</label>
      <input type="number" step="0.01" id="maintenance_cost">

      <label>Monthly Insurance ($)</label>
      <input type="number" step="0.01" id="monthly_insurance">

      <label>Monthly Fuel/Electric ($)</label>
      <input type="number" step="0.01" id="monthly_fuel">

      <button type="submit" class="btn-primary" style="margin-top:0.5rem;">Save Calculator</button>
    </form>
  </div>

  <!-- Listing Card -->
  <div class="card" x-show="tab==='list'">
    <h2 style="font-size:1.25rem; margin-bottom:0.5rem;">List of Saved Calculators</h2>
    <ul id="calcList"></ul>
  </div>

  <script>
    let calcList = [];

    function initApp() {
      fetchAllCalculators();
    }

    async function fetchAllCalculators() {
      try {
        let resp = await fetch('/api/awesome');
        if (!resp.ok) throw new Error('Could not fetch calculators');
        calcList = await resp.json();
        renderCalcList();
      } catch(err) {
        console.error('fetchAllCalculators error:', err);
      }
    }

    function renderCalcList() {
      const listEl = document.getElementById('calcList');
      listEl.innerHTML = '';
      calcList.forEach(calc => {
        let li = document.createElement('li');
        li.style.marginBottom = '0.5rem';
        li.innerHTML = `<a href="#" style="color:#007aff; text-decoration:underline;"
                          onclick="loadCalculator(${calc.id}); return false;">
                          Calculator #${calc.id} (${calc.calc_type})
                        </a>`;
        listEl.appendChild(li);
      });
    }

    async function saveCalculator(e) {
      e.preventDefault();
      const data = {
        calc_type: document.getElementById('calc_type').value,
        vehicle_price: document.getElementById('vehicle_price').value,
        rebates_and_discounts: document.getElementById('rebates_and_discounts').value,
        down_payment: document.getElementById('down_payment').value,
        term_months: document.getElementById('term_months').value,
        residual_percent: document.getElementById('residual_percent').value,
        residual_value: document.getElementById('residual_value').value,
        money_factor: document.getElementById('money_factor').value,
        tax_percent: document.getElementById('tax_percent').value,
        tax_total: document.getElementById('tax_total').value,
        capitalize_taxes: document.getElementById('capitalize_taxes').checked,
        additional_fees: document.getElementById('additional_fees').value,
        capitalize_fees: document.getElementById('capitalize_fees').checked,
        maintenance_cost: document.getElementById('maintenance_cost').value,
        monthly_insurance: document.getElementById('monthly_insurance').value,
        monthly_fuel: document.getElementById('monthly_fuel').value
      };

      try {
        let resp = await fetch('/api/awesome', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
          },
          body: JSON.stringify(data)
        });
        let result = await resp.json();
        if (!resp.ok) {
          console.error('Save error:', result);
          alert('Failed to save. Check console.');
        } else {
          alert('Calculator saved!');
          fetchAllCalculators();
        }
      } catch(err) {
        alert('Error saving. See console.');
        console.error(err);
      }
    }

    async function loadCalculator(id) {
      try {
        let resp = await fetch('/api/awesome/' + id);
        if (!resp.ok) throw new Error('Failed to load calculator ' + id);
        let calc = await resp.json();
        document.getElementById('calc_type').value = calc.calc_type ?? 'lease';
        document.getElementById('vehicle_price').value = calc.vehicle_price ?? '';
        document.getElementById('rebates_and_discounts').value = calc.rebates_and_discounts ?? '';
        document.getElementById('down_payment').value = calc.down_payment ?? '';
        document.getElementById('term_months').value = calc.term_months ?? '36';
        document.getElementById('residual_percent').value = calc.residual_percent ?? '';
        document.getElementById('residual_value').value = calc.residual_value ?? '';
        document.getElementById('money_factor').value = calc.money_factor ?? '';
        document.getElementById('tax_percent').value = calc.tax_percent ?? '';
        document.getElementById('tax_total').value = calc.tax_total ?? '';
        document.getElementById('capitalize_taxes').checked = calc.capitalize_taxes ? true : false;
        document.getElementById('additional_fees').value = calc.additional_fees ?? '';
        document.getElementById('capitalize_fees').checked = calc.capitalize_fees ? true : false;
        document.getElementById('maintenance_cost').value = calc.maintenance_cost ?? '';
        document.getElementById('monthly_insurance').value = calc.monthly_insurance ?? '';
        document.getElementById('monthly_fuel').value = calc.monthly_fuel ?? '';
        document.querySelector('[x-data]').__x.$data.tab = 'calc'; // Switch to calc tab
      } catch(err) {
        alert('Failed to load. Check console.');
        console.error(err);
      }
    }
  </script>
</body>
</html>