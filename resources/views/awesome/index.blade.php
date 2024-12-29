<!DOCTYPE html>
<html lang="en" x-data="{ tab: 'calc' }">
<head>
  <meta charset="UTF-8">
  <title>Awesome Calculator SPA</title>

  <!-- Minimal Alpine for tab switching only -->
  <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

  <!-- If removing CSRF, do so. Otherwise: -->
  <meta name="csrf-token" content="{{ csrf_token() }}">
  @vite(['resources/css/app.css','resources/js/app.js'])

  <style>
    body {
      margin: 0;
      padding: 1rem;
      background-color: #1e1e2f;
      color: #eee;
      font-family: sans-serif;
    }
    .tab-button {
      background-color: #333;
      color: #aaa;
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
      background-color: #2a2a3c;
      border-radius: 8px;
      padding: 1rem;
      margin-bottom: 1rem;
    }
    input, select {
      background-color: #333;
      border: 1px solid #555;
      color: #fff;
      padding: 0.5rem;
      border-radius: 4px;
      width: 100%;
      box-sizing: border-box;
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

  <h1 class="text-2xl font-bold mb-4">Awesome Calculator SPA</h1>

  <div class="flex gap-2 mb-4">
    <button class="tab-button" :class="{ 'active': tab==='calc' }" @click="tab='calc'">
      Calculator
    </button>
    <button class="tab-button" :class="{ 'active': tab==='list' }" @click="tab='list'">
      List of Calculators
    </button>
  </div>

  <div class="card" x-show="tab==='calc'">
    <h2 class="text-xl font-semibold mb-2">Finance/Lease Calculator</h2>
    <form onsubmit="saveCalculator(event)">
      <div class="mb-2">
        <label>Calculation Type</label>
        <select id="calc_type">
          <option value="lease">Lease</option>
          <option value="financing">Financing</option>
          <option value="cash">Cash</option>
        </select>
      </div>
      <div class="mb-2">
        <label>Vehicle Price</label>
        <input type="number" step="0.01" id="vehicle_price">
      </div>
      <div class="mb-2">
        <label>Rebates & Discounts</label>
        <input type="number" step="0.01" id="rebates_and_discounts">
      </div>
      <div class="mb-2">
        <label>Down Payment</label>
        <input type="number" step="0.01" id="down_payment">
      </div>
      <div class="mb-2">
        <label>Term (Months)</label>
        <input type="number" id="term_months" value="36">
      </div>
      <div class="mb-2">
        <label>Residual (%)</label>
        <input type="number" step="0.01" id="residual_percent">
      </div>
      <div class="mb-2">
        <label>Residual Value ($)</label>
        <input type="number" step="0.01" id="residual_value">
      </div>
      <div class="mb-2">
        <label>Money Factor</label>
        <input type="number" step="0.00001" id="money_factor">
      </div>
      <div class="mb-2">
        <label>Tax (%)</label>
        <input type="number" step="0.01" id="tax_percent">
      </div>
      <div class="mb-2">
        <label>Tax (Total $)</label>
        <input type="number" step="0.01" id="tax_total">
      </div>
      <div class="mb-2">
        <label><input type="checkbox" id="capitalize_taxes"> Capitalize Taxes</label>
      </div>
      <div class="mb-2">
        <label>Additional Fees ($)</label>
        <input type="number" step="0.01" id="additional_fees">
      </div>
      <div class="mb-2">
        <label><input type="checkbox" id="capitalize_fees"> Capitalize Fees</label>
      </div>
      <div class="mb-2">
        <label>Yearly Maintenance ($)</label>
        <input type="number" step="0.01" id="maintenance_cost">
      </div>
      <div class="mb-2">
        <label>Monthly Insurance ($)</label>
        <input type="number" step="0.01" id="monthly_insurance">
      </div>
      <div class="mb-2">
        <label>Monthly Fuel/Electric ($)</label>
        <input type="number" step="0.01" id="monthly_fuel">
      </div>
      <button type="submit" class="btn-primary mt-2">Save Calculator</button>
    </form>
  </div>

  <div class="card" x-show="tab==='list'">
    <h2 class="text-xl font-semibold mb-2">List of Saved Calculators</h2>
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
        li.innerHTML = `<a href="#" style="color:#4af" onclick="loadCalculator(${calc.id})">
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
            // remove or comment out if you don't need CSRF
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
        // Fill the form
        document.getElementById('calc_type').value = calc.calc_type;
        document.getElementById('vehicle_price').value = calc.vehicle_price;
        document.getElementById('rebates_and_discounts').value = calc.rebates_and_discounts;
        document.getElementById('down_payment').value = calc.down_payment;
        document.getElementById('term_months').value = calc.term_months;
        document.getElementById('residual_percent').value = calc.residual_percent;
        document.getElementById('residual_value').value = calc.residual_value;
        document.getElementById('money_factor').value = calc.money_factor;
        document.getElementById('tax_percent').value = calc.tax_percent;
        document.getElementById('tax_total').value = calc.tax_total;
        document.getElementById('capitalize_taxes').checked = calc.capitalize_taxes;
        document.getElementById('additional_fees').value = calc.additional_fees;
        document.getElementById('capitalize_fees').checked = calc.capitalize_fees;
        document.getElementById('maintenance_cost').value = calc.maintenance_cost;
        document.getElementById('monthly_insurance').value = calc.monthly_insurance;
        document.getElementById('monthly_fuel').value = calc.monthly_fuel;
        document.querySelector('[x-data]').__x.$data.tab = 'calc'; // switch tab
      } catch(err) {
        alert('Failed to load. Check console.');
        console.error(err);
      }
    }
  </script>
</body>
</html>