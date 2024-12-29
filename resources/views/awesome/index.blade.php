<!DOCTYPE html>
<html lang="en" x-data="{ currentPage:'calculator', tab:'lease', calcList:[] }" x-init="initMainApp()">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
  <title>Fleet Ownership Cost Analyzer</title>
  <link rel="manifest" href="/manifest.json">
  <meta name="theme-color" content="#1e1e2f">

  <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <style>
    body {
      margin: 0;
      background: #1e1e2f;
      color: #eee;
      font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen, Ubuntu, Cantarell, "Open Sans", "Helvetica Neue", sans-serif;
      font-size: 16px;
    }
    .app-container {
      display: flex;
      flex-direction: column;
      height: 100vh;
      overflow: auto;
    }
    .top-nav {
      background: #2a2a3c;
      color: #fff;
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 0.75rem 1rem;
    }
    .top-nav .nav-links button {
      background: #444;
      color: #bbb;
      border: none;
      padding: 0.5rem 1rem;
      margin-right: 0.5rem;
      border-radius: 6px;
      cursor: pointer;
    }
    .top-nav .nav-links button.active {
      background: #007aff;
      color: #fff;
    }

    .content {
      flex: 1;
      display: flex;
      flex-direction: column;
      padding: 1rem;
      gap: 1rem;
    }
    .card {
      background: #2a2a3c;
      border-radius: 12px;
      padding: 1rem;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
      display: flex;
      flex-direction: column;
      gap: 1rem;
      color: #fff;
    }
    label {
      display: block;
      font-weight: 500;
      margin-bottom: 0.5rem;
      color: #eee;
    }
    input[type="number"], input[type="text"] {
      background: #333;
      color: #fff;
      border: 1px solid #666;
      border-radius: 6px;
      padding: 0.75rem;
      font-size: 1rem;
      width: 100%;
    }
    .checkbox-label {
      display: flex;
      align-items: center;
      color: #eee;
      gap: 0.5rem;
    }
    .small-label {
      color: #eee;
      font-size: 0.9rem;
      font-weight: 500;
    }
    .flex-row {
      display: flex;
      gap: 1rem;
      align-items: center;
      flex-wrap: wrap;
    }
    .cost-indicator {
      padding: 0.5rem;
      border-radius: 6px;
      display: inline-block;
      margin-bottom: 1rem;
      font-weight: bold;
    }
    .green { background-color: #4caf50; color: #fff; }
    .yellow { background-color: #ffeb3b; color: #000; }
    .red { background-color: #f44336; color: #fff; }
    .btn-dark {
      background: #555;
      color: #eee;
      border: 1px solid #666;
      padding: 0.5rem 1rem;
      border-radius: 5px;
      cursor: pointer;
      margin-top: 1rem;
    }
    .btn-dark:hover {
      background: #777;
    }
    .hidden { display: none; }
  </style>

  <script>
    // Service Worker registration
    if ('serviceWorker' in navigator) {
      navigator.serviceWorker.register('/service-worker.js');
    }

    // MAIN SPA CODE
    function initMainApp() {
      // default page is "calculator"
      // we can fetch the entire list of calcList in the background if we want
    }

    async function fetchAllCalcs() {
      try {
        let resp = await fetch('/api/awesome');
        if (!resp.ok) throw new Error('Failed to get list');
        let data = await resp.json();
        // we store it in Alpine's calcList
        document.querySelector('[x-data]').__x.$data.calcList = data;
      } catch(err) {
        console.error('Error fetching calc list:', err);
      }
    }

    async function loadCalculatorFromList(id) {
      // fetch single record
      try {
        let resp = await fetch('/api/awesome/' + id);
        if(!resp.ok) throw new Error('Load error: ' + id);
        let calc = await resp.json();
        // set the type => tab
        let newTab = calc.calc_type || 'lease';
        document.querySelector('[x-data]').__x.$data.tab = newTab;
        window.selectedTab = newTab;

        if(newTab==='lease') {
          // fill fields
          document.getElementById('leaseVehiclePrice').value = calc.vehicle_price || '';
          document.getElementById('leaseRebatesAndDiscounts').value = calc.rebates_and_discounts || '';
          document.getElementById('leaseDownPayment').value = calc.down_payment || '';
          document.getElementById('leaseTermMonths').value = calc.term_months || '36';
          document.getElementById('residualValue').value = calc.residual_percent || '';
          document.getElementById('residualValueTotal').value = calc.residual_value || '';
          document.getElementById('moneyFactor').value = calc.money_factor || '';
          document.getElementById('leaseTaxPercentage').value = calc.tax_percent || '';
          document.getElementById('leaseTaxTotal').value = calc.tax_total || '';
          document.getElementById('leaseAddTaxesToLease').checked = calc.capitalize_taxes ? true : false;
          document.getElementById('leaseAdditionalFees').value = calc.additional_fees || '';
          document.getElementById('leaseAddFeesToLease').checked = calc.capitalize_fees ? true : false;
          document.getElementById('leaseMaintenanceCost').value = calc.maintenance_cost || '';
          document.getElementById('leaseMonthlyInsurance').value = calc.monthly_insurance || '';
          document.getElementById('leaseMonthlyFuel').value = calc.monthly_fuel || '';
        } else if(newTab==='cash') {
          document.getElementById('cashVehiclePrice').value = calc.vehicle_price || '';
          document.getElementById('cashTaxPercentage').value = calc.tax_percent || '';
          document.getElementById('cashAddTaxesToCash').checked = calc.capitalize_taxes ? true : false;
          document.getElementById('cashAdditionalFees').value = calc.additional_fees || '';
          document.getElementById('cashAddFeesToCash').checked = calc.capitalize_fees ? true : false;
          document.getElementById('cashMaintenanceCost').value = calc.maintenance_cost || '';
          document.getElementById('cashMonthlyInsurance').value = calc.monthly_insurance || '';
          document.getElementById('cashMonthlyFuel').value = calc.monthly_fuel || '';
        } else {
          // do financing if you implement
        }
        // go back to "calculator" page
        document.querySelector('[x-data]').__x.$data.currentPage = 'calculator';
        updateCalculations();
      } catch(err) {
        console.error('loadCalculatorFromList error:', err);
        alert('Failed to load calculator. See console.');
      }
    }

    // The function to POST minimal data to /api/awesome
    async function saveCalculatorToApi() {
      let calc_type = window.selectedTab; 
      let vehicle_price = 0;

      if (calc_type==='lease') {
        vehicle_price = document.getElementById('leaseVehiclePrice').value || 0;
      } else if (calc_type==='cash') {
        vehicle_price = document.getElementById('cashVehiclePrice').value || 0;
      } else {
        // handle or skip financing
      }

      const data = {
        calc_type,
        vehicle_price
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
          alert('Calculator saved to /api/awesome!');
        }
      } catch(err) {
        console.error('Saving error:', err);
        alert('Error. See console.');
      }
    }

    // REPLACE your existing calculation logic (the long code)...

    let selectedTab = 'lease';
    let lastChangedTaxField = 'percentage';
    let lastChangedResidualField = 'percentage';

    function selectMainPage(pageName) {
      document.querySelector('[x-data]').__x.$data.currentPage = pageName;
      if(pageName==='saved'){
        // fetch the entire list
        fetchAllCalcs();
      }
    }

    function selectTab(tabName) {
      selectedTab = tabName;
      document.querySelector('[x-data]').__x.$data.tab = tabName;
      document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
      document.getElementById(tabName + 'Tab').classList.add('active');
      updateFormVisibility();
      updateCalculations();
    }

    // ... keep your existing syncLeaseTaxes, syncLeaseResidual, etc. ...
    // ... keep your existing calculateLeaseCosts, calculateCashCosts ...
    // ... keep your existing updateCalculations, updateUI ...
  </script>
</head>
<body>
  <div class="app-container" x-data>
    <!-- Top Nav Bar -->
    <div class="top-nav">
      <div class="nav-links">
        <button :class="{ 'active': currentPage==='calculator' }" @click="selectMainPage('calculator')">Calculator</button>
        <button :class="{ 'active': currentPage==='saved' }" @click="selectMainPage('saved')">Saved Calculators</button>
      </div>
      <div class="app-title">
        Fleet Ownership Cost Analyzer
      </div>
    </div>

    <div class="content">

      <!-- PAGE: Calculator -->
      <div x-show="currentPage==='calculator'" style="display:none;">
        <div class="card" id="inputSection">
          <!-- EXACT same tab structure for lease/financing/cash -->
          <div class="tab-bar">
            <div id="leaseTab" class="tab" @click="selectTab('lease')">Lease</div>
            <div id="financingTab" class="tab" @click="selectTab('financing')">Financing</div>
            <div id="cashTab" class="tab" @click="selectTab('cash')">Cash</div>
          </div>

          <!-- Lease Form -->
          <div id="leaseForm" style="display:none;">
            <label>Vehicle Price ($):
              <input type="number" id="leaseVehiclePrice" oninput="leaseVehicleOrRebatesChanged()">
            </label>
            <label>Total Rebates and Discounts ($):
              <input type="number" id="leaseRebatesAndDiscounts" oninput="leaseVehicleOrRebatesChanged()">
            </label>
            <label>Down Payment ($):
              <input type="number" id="leaseDownPayment" onchange="updateCalculations()">
            </label>
            <label>Lease Term (Months):
              <input type="number" id="leaseTermMonths" onchange="updateCalculations()">
            </label>
            <div class="flex-row">
              <span class="small-label">Residual (%):</span>
              <input type="number" id="residualValue" oninput="residualPercentageChanged()" style="width:80px;">
              <span class="small-label">Residual ($):</span>
              <input type="number" id="residualValueTotal" oninput="residualTotalChanged()" style="width:80px;">
            </div>
            <label>Money Factor:
              <input type="number" id="moneyFactor" onchange="updateCalculations()">
            </label>
            <div class="flex-row">
              <span class="small-label">Taxes (%):</span>
              <input type="number" id="leaseTaxPercentage" oninput="leaseTaxPercentageChanged()" style="width:80px;">
              <span class="small-label">Tax ($):</span>
              <input type="number" id="leaseTaxTotal" oninput="leaseTaxTotalChanged()" style="width:80px;">
            </div>
            <label class="checkbox-label">
              <input type="checkbox" id="leaseAddTaxesToLease" onchange="updateCalculations()"> Capitalize Taxes into Lease
            </label>
            <label>Additional Fees ($):
              <input type="number" id="leaseAdditionalFees" onchange="updateCalculations()">
            </label>
            <label class="checkbox-label">
              <input type="checkbox" id="leaseAddFeesToLease" onchange="updateCalculations()"> Capitalize Fees into Lease
            </label>
            <label>Yearly Maintenance ($):
              <input type="number" id="leaseMaintenanceCost" onchange="updateCalculations()">
            </label>
            <label>Monthly Insurance ($):
              <input type="number" id="leaseMonthlyInsurance" onchange="updateCalculations()">
            </label>
            <label>Monthly Fuel/Electric ($):
              <input type="number" id="leaseMonthlyFuel" onchange="updateCalculations()">
            </label>
            <button class="btn-dark" onclick="saveCalculatorToApi()">Save Lease to /api/awesome</button>
          </div>

          <!-- Financing Form -->
          <div id="financingForm" style="display:none;">
            <p style="color:#ccc;">Financing form (TBD)</p>
          </div>

          <!-- Cash Form -->
          <div id="cashForm" style="display:none;">
            <label>Vehicle Price ($):
              <input type="number" id="cashVehiclePrice" onchange="updateCalculations()">
            </label>
            <label>Taxes (%):
              <input type="number" id="cashTaxPercentage" onchange="updateCalculations()">
            </label>
            <label class="checkbox-label">
              <input type="checkbox" id="cashAddTaxesToCash" onchange="updateCalculations()"> Add Taxes to Vehicle Price
            </label>
            <label>Additional Fees ($):
              <input type="number" id="cashAdditionalFees" onchange="updateCalculations()">
            </label>
            <label class="checkbox-label">
              <input type="checkbox" id="cashAddFeesToCash" onchange="updateCalculations()"> Add Fees to Vehicle Price
            </label>
            <label>Yearly Maintenance ($):
              <input type="number" id="cashMaintenanceCost" onchange="updateCalculations()">
            </label>
            <label>Monthly Insurance ($):
              <input type="number" id="cashMonthlyInsurance" onchange="updateCalculations()">
            </label>
            <label>Monthly Fuel/Electric ($):
              <input type="number" id="cashMonthlyFuel" onchange="updateCalculations()">
            </label>
            <button class="btn-dark" onclick="saveCalculatorToApi()">Save Cash to /api/awesome</button>
          </div>
        </div>

        <!-- Results Card -->
        <div class="card results-section hidden" id="results">
          <h2>Ownership Cost Breakdown</h2>
          <div id="monthlyPayment" class="cost-indicator"></div>
          <div id="totalUpfrontCost" class="detail"></div>
          <div id="totalYearlyCost" class="detail"></div>
          <div id="totalMonthlyCost" class="detail"></div>
          <div id="leaseExtraInfo" style="display: none;">
            <h3 style="margin-top:1rem; margin-bottom:0.5rem;">Additional Lease Details</h3>
            <div id="aprEquivalent" class="detail"></div>
            <div id="negotiatedPriceOutput" class="detail"></div>
            <div id="netCapCostOutput" class="detail"></div>
            <div id="residualValueOutput" class="detail"></div>
            <div id="monthlyDepreciationOutput" class="detail"></div>
            <div id="monthlyFinanceChargeOutput" class="detail"></div>
            <div id="totalDepreciationOutput" class="detail"></div>
            <div id="totalFinanceChargesOutput" class="detail"></div>
            <div id="totalLeasePaymentsOutput" class="detail"></div>
          </div>
        </div>
      </div>

      <!-- PAGE: Saved Calculators -->
      <div x-show="currentPage==='saved'" style="display:none;">
        <div class="card">
          <h2>My Saved Calculators</h2>
          <button class="btn-dark" @click="fetchAllCalcs()">Refresh List</button>
          <ul style="list-style:none; margin:0; padding:0;">
            <template x-for="calc in calcList" :key="calc.id">
              <li style="margin:0.5rem 0;">
                <a href="#" style="color:#66afff; text-decoration:underline;"
                   @click.prevent="loadCalculatorFromList(calc.id)">
                  Calculator #<span x-text="calc.id"></span> (<span x-text="calc.calc_type"></span>)
                </a>
              </li>
            </template>
          </ul>
        </div>
      </div>
    </div>
  </div>
</body>
</html>