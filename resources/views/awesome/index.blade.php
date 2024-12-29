<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Awesome Calculator SPA</title>

  <!-- Alpine loaded with defer -->
  <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

  <!-- If you want to remove CSRF or handle differently, do so. For demonstration, we'll just keep it. -->
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
      box-sizing: border-box; /* ensure consistent sizing */
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

<!-- Alpine main container -->
<body x-data="awesomeCalcApp()" x-init="init()">
  <h1 class="text-2xl font-bold mb-4">Awesome Calculator SPA</h1>

  <div class="flex gap-2 mb-4">
    <button class="tab-button" :class="{ 'active': tab==='calc' }" @click="tab='calc'">Calculator</button>
    <button class="tab-button" :class="{ 'active': tab==='list' }" @click="tab='list'">List of Calculators</button>
  </div>

  <!-- Calculator Card -->
  <div x-show="tab === 'calc'" class="card">
    <h2 class="text-xl font-semibold mb-2">Finance/Lease Calculator</h2>
    <form @submit.prevent="saveCalculator" class="space-y-2">
      <div>
        <label>Calculation Type</label>
        <select x-model="calcForm.calc_type">
          <option value="lease">Lease</option>
          <option value="financing">Financing</option>
          <option value="cash">Cash</option>
        </select>
      </div>
      <div>
        <label>Vehicle Price</label>
        <input type="number" step="0.01" x-model="calcForm.vehicle_price">
      </div>
      <div>
        <label>Rebates & Discounts</label>
        <input type="number" step="0.01" x-model="calcForm.rebates_and_discounts">
      </div>
      <div>
        <label>Down Payment</label>
        <input type="number" step="0.01" x-model="calcForm.down_payment">
      </div>
      <div>
        <label>Term (Months)</label>
        <input type="number" x-model="calcForm.term_months">
      </div>
      <div>
        <label>Residual (%)</label>
        <input type="number" step="0.01" x-model="calcForm.residual_percent">
      </div>
      <div>
        <label>Residual Value ($)</label>
        <input type="number" step="0.01" x-model="calcForm.residual_value">
      </div>
      <div>
        <label>Money Factor</label>
        <input type="number" step="0.00001" x-model="calcForm.money_factor">
      </div>
      <div>
        <label>Tax (%)</label>
        <input type="number" step="0.01" x-model="calcForm.tax_percent">
      </div>
      <div>
        <label>Tax (Total $)</label>
        <input type="number" step="0.01" x-model="calcForm.tax_total">
      </div>
      <div>
        <label><input type="checkbox" x-model="calcForm.capitalize_taxes"> Capitalize Taxes</label>
      </div>
      <div>
        <label>Additional Fees ($)</label>
        <input type="number" step="0.01" x-model="calcForm.additional_fees">
      </div>
      <div>
        <label><input type="checkbox" x-model="calcForm.capitalize_fees"> Capitalize Fees</label>
      </div>
      <div>
        <label>Yearly Maintenance ($)</label>
        <input type="number" step="0.01" x-model="calcForm.maintenance_cost">
      </div>
      <div>
        <label>Monthly Insurance ($)</label>
        <input type="number" step="0.01" x-model="calcForm.monthly_insurance">
      </div>
      <div>
        <label>Monthly Fuel/Electric ($)</label>
        <input type="number" step="0.01" x-model="calcForm.monthly_fuel">
      </div>
      <button type="submit" class="btn-primary mt-2">Save Calculator</button>
    </form>
  </div>

  <!-- Listing Card -->
  <div x-show="tab === 'list'" class="card">
    <h2 class="text-xl font-semibold mb-2">List of Saved Calculators</h2>
    <ul>
      <template x-for="calc in calcList" :key="calc.id">
        <li class="my-2">
          <a href="#" @click.prevent="loadCalculator(calc.id)" class="underline text-blue-300">
            Calculator #<span x-text="calc.id"></span> (<span x-text="calc.calc_type"></span>)
          </a>
        </li>
      </template>
    </ul>
  </div>

  <script>
    function awesomeCalcApp() {
      return {
        tab: 'calc',
        calcForm: {
          calc_type: 'lease',
          vehicle_price: '',
          rebates_and_discounts: '',
          down_payment: '',
          term_months: '',
          residual_percent: '',
          residual_value: '',
          money_factor: '',
          tax_percent: '',
          tax_total: '',
          capitalize_taxes: false,
          additional_fees: '',
          capitalize_fees: false,
          maintenance_cost: '',
          monthly_insurance: '',
          monthly_fuel: ''
        },
        calcList: [],
        init() {
          console.log("Alpine init: Attempting to fetch all calculators...");
          this.fetchAllCalculators();
        },
        async fetchAllCalculators() {
          try {
            let resp = await fetch('/api/awesome');
            if (!resp.ok) throw new Error('Failed to fetch list');
            this.calcList = await resp.json();
          } catch (err) {
            console.error('fetchAllCalculators error:', err);
          }
        },
        async saveCalculator() {
          try {
            // We'll always POST a new one. If you want edit, you'd do a separate approach.
            let resp = await fetch('/api/awesome', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                // remove X-CSRF if you don't want to handle tokens
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
              },
              body: JSON.stringify(this.calcForm)
            });
            let data = await resp.json();
            if (!resp.ok) {
              alert('Error saving. See console.');
              console.error(data);
            } else {
              alert('Calculator saved!');
              this.fetchAllCalculators();
              this.tab = 'list';
            }
          } catch (err) {
            alert('Save error. Check console.');
            console.error('saveCalculator error:', err);
          }
        },
        async loadCalculator(id) {
          try {
            let resp = await fetch('/api/awesome/' + id);
            if (!resp.ok) throw new Error('Load error');
            let data = await resp.json();
            // Populate the form
            this.calcForm = {
              calc_type: data.calc_type || 'lease',
              vehicle_price: data.vehicle_price || '',
              rebates_and_discounts: data.rebates_and_discounts || '',
              down_payment: data.down_payment || '',
              term_months: data.term_months || '',
              residual_percent: data.residual_percent || '',
              residual_value: data.residual_value || '',
              money_factor: data.money_factor || '',
              tax_percent: data.tax_percent || '',
              tax_total: data.tax_total || '',
              capitalize_taxes: data.capitalize_taxes ? true : false,
              additional_fees: data.additional_fees || '',
              capitalize_fees: data.capitalize_fees ? true : false,
              maintenance_cost: data.maintenance_cost || '',
              monthly_insurance: data.monthly_insurance || '',
              monthly_fuel: data.monthly_fuel || ''
            };
            this.tab = 'calc';
          } catch (err) {
            alert('Failed to load that calculator.');
            console.error(err);
          }
        }
      }
    }
  </script>
</body>
</html>