<!DOCTYPE html>
<html lang="en" x-data="awesomeCalcApp()" x-init="init()">
<head>
  <meta charset="UTF-8">
  <title>Awesome Calculator at /awesome</title>
  <!-- Vite or your compiled assets -->
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- Make sure Alpine.js is loaded BEFORE body or at least with defer -->
  <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

  <style>
    body {
      background-color: #1e1e2f; /* dark background */
      color: #eee; /* light text */
      font-family: sans-serif;
      margin: 0;
      padding: 1rem;
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
<body>
  <h1 class="text-2xl font-bold mb-4">Awesome Calculator SPA</h1>

  <div class="flex gap-2 mb-4">
    <button class="tab-button" :class="{ 'active': tab==='calc' }" @click="tab='calc'">Calculator</button>
    <button class="tab-button" :class="{ 'active': tab==='list' }" @click="tab='list'">List of Calculators</button>
  </div>

  <!-- Calculator Tab -->
  <div x-show="tab === 'calc'" class="card">
    <h2 class="text-xl font-semibold mb-2">Finance/Lease Calculator</h2>
    <form @submit.prevent="saveCalculator">
      <div class="mb-2">
        <label>Calculation Type</label>
        <select x-model="calcForm.calc_type">
          <option value="lease">Lease</option>
          <option value="financing">Financing</option>
          <option value="cash">Cash</option>
        </select>
      </div>
      <div class="mb-2">
        <label>Vehicle Price</label>
        <input type="number" step="0.01" x-model="calcForm.vehicle_price"/>
      </div>
      <div class="mb-2">
        <label>Rebates & Discounts</label>
        <input type="number" step="0.01" x-model="calcForm.rebates_and_discounts"/>
      </div>
      <div class="mb-2">
        <label>Down Payment</label>
        <input type="number" step="0.01" x-model="calcForm.down_payment"/>
      </div>
      <div class="mb-2">
        <label>Term (Months)</label>
        <input type="number" x-model="calcForm.term_months"/>
      </div>
      <div class="mb-2">
        <label>Residual (%)</label>
        <input type="number" step="0.01" x-model="calcForm.residual_percent"/>
      </div>
      <div class="mb-2">
        <label>Residual Value ($)</label>
        <input type="number" step="0.01" x-model="calcForm.residual_value"/>
      </div>
      <div class="mb-2">
        <label>Money Factor</label>
        <input type="number" step="0.00001" x-model="calcForm.money_factor"/>
      </div>
      <div class="mb-2">
        <label>Tax (%)</label>
        <input type="number" step="0.01" x-model="calcForm.tax_percent"/>
      </div>
      <div class="mb-2">
        <label>Tax (Total $)</label>
        <input type="number" step="0.01" x-model="calcForm.tax_total"/>
      </div>
      <div class="mb-2">
        <label><input type="checkbox" x-model="calcForm.capitalize_taxes"/> Capitalize Taxes</label>
      </div>
      <div class="mb-2">
        <label>Additional Fees ($)</label>
        <input type="number" step="0.01" x-model="calcForm.additional_fees"/>
      </div>
      <div class="mb-2">
        <label><input type="checkbox" x-model="calcForm.capitalize_fees"/> Capitalize Fees</label>
      </div>
      <div class="mb-2">
        <label>Yearly Maintenance ($)</label>
        <input type="number" step="0.01" x-model="calcForm.maintenance_cost"/>
      </div>
      <div class="mb-2">
        <label>Monthly Insurance ($)</label>
        <input type="number" step="0.01" x-model="calcForm.monthly_insurance"/>
      </div>
      <div class="mb-2">
        <label>Monthly Fuel/Electric ($)</label>
        <input type="number" step="0.01" x-model="calcForm.monthly_fuel"/>
      </div>
      <button type="submit" class="btn-primary mt-2">Save Calculator</button>
    </form>
  </div>

  <!-- Listing Tab -->
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
        async init() {
          console.log("Alpine initialized. Fetching calculators...");
          await this.fetchAllCalculators();
        },
        async fetchAllCalculators() {
          try {
            let resp = await fetch('/api/awesome');
            this.calcList = await resp.json();
          } catch (err) {
            console.error('fetchAllCalculators error:', err);
          }
        },
        async saveCalculator() {
          try {
            let resp = await fetch('/api/awesome', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector("meta[name='csrf-token']").content
              },
              body: JSON.stringify(this.calcForm)
            });
            let data = await resp.json();
            if (!resp.ok) {
              console.error('Error saving:', data);
              alert('Save failed.');
            } else {
              alert('Calculator saved!');
              await this.fetchAllCalculators();
              this.tab = 'list';
            }
          } catch (err) {
            console.error('saveCalculator error:', err);
            alert('Save error, see console.');
          }
        },
        async loadCalculator(id) {
          try {
            let resp = await fetch('/api/awesome/' + id);
            if (!resp.ok) throw new Error('Load error');
            let data = await resp.json();
            // populate
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
            console.error('loadCalculator error:', err);
            alert('Failed to load calculator');
          }
        }
      };
    }
  </script>
</body>
</html>