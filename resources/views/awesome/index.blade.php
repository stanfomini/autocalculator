<!DOCTYPE html>
<html lang="en" x-data="{ currentView:'calculator', calcList:[] }">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
  <title>Fleet Ownership Cost Analyzer</title>
  <link rel="manifest" href="/manifest.json">
  <meta name="theme-color" content="#1e1e2f">

  <!-- Alpine for top nav only -->
  <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
  <!-- Optional CSRF -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <style>
    body {
      margin: 0;
      background: #1e1e2f;
      color: #eee;
      font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen, Ubuntu, Cantarell, "Open Sans", "Helvetica Neue", sans-serif;
      font-size: 16px;
    }
    .top-nav {
      background: #2a2a3c;
      color: #fff;
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 0.75rem 1rem;
    }
    .top-nav .nav-section button {
      background: #444;
      color: #bbb;
      border: none;
      padding: 0.5rem 1rem;
      margin-right: 0.5rem;
      border-radius: 6px;
      cursor: pointer;
    }
    .top-nav .nav-section button.active {
      background: #007aff;
      color: #fff;
      font-weight: bold;
    }
    .content {
      padding: 1rem;
    }
    .card {
      background: #2a2a3c;
      border-radius: 12px;
      padding: 1rem;
      margin-bottom: 1rem;
      color: #fff;
    }
    /* Keep the old calculator styling from your previous code... */
    input[type="number"], input[type="text"] {
      background: #333;
      color: #fff;
      border: 1px solid #666;
      border-radius: 6px;
      padding: 0.75rem;
      font-size: 1rem;
      width: 100%;
    }
    .tab {
      background: #444;
      color: #bbb;
      padding: 0.5rem 1rem;
      margin-right: 0.5rem;
      border-radius: 6px;
      cursor: pointer;
      border: none;
    }
    .tab.active {
      background: #007aff;
      color: #fff;
    }
    /* etc... */
    .hidden { display: none; }
  </style>

  <!-- The existing calculator script (unchanged) -->
  <script>
    // Service Worker registration
    if ('serviceWorker' in navigator) {
      navigator.serviceWorker.register('/service-worker.js');
    }

    // We preserve your original approach for the calculator tabs:
    let selectedTab = 'lease';
    let lastChangedTaxField = 'percentage';
    let lastChangedResidualField = 'percentage';

    function selectTab(tabName) {
      selectedTab = tabName;
      document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
      document.getElementById(tabName+'Tab').classList.add('active');
      updateFormVisibility();
      updateCalculations();
    }
    function updateFormVisibility() {
      document.getElementById('leaseForm').style.display = (selectedTab==='lease') ? 'block' : 'none';
      document.getElementById('financingForm').style.display = (selectedTab==='financing') ? 'block' : 'none';
      document.getElementById('cashForm').style.display = (selectedTab==='cash') ? 'block' : 'none';
    }
    // ... keep all your existing logic: syncLeaseTaxes, syncLeaseResidual, 
    // calculateLeaseCosts, calculateCashCosts, updateUI, etc.

    async function fetchCalcList() {
      try {
        let resp = await fetch('/api/awesome');
        if (!resp.ok) throw new Error('Failed to fetch');
        let data = await resp.json();
        // store in Alpine's calcList
        document.querySelector('[x-data]').__x.$data.calcList = data;
      } catch(err) {
        console.error('Error fetching list:', err);
      }
    }

    async function loadCalculatorFromList(id) {
      try {
        let resp = await fetch('/api/awesome/' + id);
        if(!resp.ok) throw new Error('Load error ' + id);
        let calc = await resp.json();
        // read calc_type => set selectedTab
        selectedTab = calc.calc_type || 'lease';
        document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
        document.getElementById(selectedTab+'Tab').classList.add('active');
        updateFormVisibility();

        // fill in fields
        if(selectedTab==='lease') {
          document.getElementById('leaseVehiclePrice').value = calc.vehicle_price || '';
          document.getElementById('leaseRebatesAndDiscounts').value = calc.rebates_and_discounts || '';
          document.getElementById('leaseDownPayment').value = calc.down_payment || '';
          document.getElementById('leaseTermMonths').value = calc.term_months || '36';
          document.getElementById('residualValue').value = calc.residual_percent || '';
          document.getElementById('residualValueTotal').value = calc.residual_value || '';
          document.getElementById('moneyFactor').value = calc.money_factor || '';
          document.getElementById('leaseTaxPercentage').value = calc.tax_percent || '';
          document.getElementById('leaseTaxTotal').value = calc.tax_total || '';
          document.getElementById('leaseAddTaxesToLease').checked = (calc.capitalize_taxes ? true : false);
          document.getElementById('leaseAdditionalFees').value = calc.additional_fees || '';
          document.getElementById('leaseAddFeesToLease').checked = (calc.capitalize_fees ? true : false);
          document.getElementById('leaseMaintenanceCost').value = calc.maintenance_cost || '';
          document.getElementById('leaseMonthlyInsurance').value = calc.monthly_insurance || '';
          document.getElementById('leaseMonthlyFuel').value = calc.monthly_fuel || '';
        } else if (selectedTab==='cash') {
          document.getElementById('cashVehiclePrice').value = calc.vehicle_price || '';
          document.getElementById('cashTaxPercentage').value = calc.tax_percent || '';
          document.getElementById('cashAddTaxesToCash').checked = (calc.capitalize_taxes ? true : false);
          document.getElementById('cashAdditionalFees').value = calc.additional_fees || '';
          document.getElementById('cashAddFeesToCash').checked = (calc.capitalize_fees ? true : false);
          document.getElementById('cashMaintenanceCost').value = calc.maintenance_cost || '';
          document.getElementById('cashMonthlyInsurance').value = calc.monthly_insurance || '';
          document.getElementById('cashMonthlyFuel').value = calc.monthly_fuel || '';
        } else {
          // financing (not yet fully implemented)
        }
        // finally recalc
        updateCalculations();
        // switch page in Alpine to "calculator"
        document.querySelector('[x-data]').__x.$data.currentView='calculator';
      } catch(err) {
        console.error('loadCalculatorFromList error:', err);
        alert('Failed to load. Check console.');
      }
    }

    // Minimal example for saving to /api/awesome
    async function saveCalculatorToApi() {
      let calc_type = selectedTab; // 'lease','financing','cash'
      let vehicle_price = 0;
      if(calc_type==='lease'){
        vehicle_price = document.getElementById('leaseVehiclePrice').value || 0;
      } else if (calc_type==='cash'){
        vehicle_price = document.getElementById('cashVehiclePrice').value || 0;
      }

      const data = { calc_type, vehicle_price };

      try {
        let resp = await fetch('/api/awesome', {
          method: 'POST',
          headers: {
            'Content-Type':'application/json',
            'Accept':'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
          },
          body: JSON.stringify(data)
        });
        let result = await resp.json();
        if(!resp.ok){
          console.error('Save error:', result);
          alert('Failed to save. See console.');
        } else {
          alert('Saved to /api/awesome');
        }
      } catch(err) {
        console.error('saveCalculatorToApi error:', err);
        alert('Error. See console.');
      }
    }

    // Keep your existing code for the calculations: leaseTaxPercentageChanged, syncLeaseResidual, etc.
    // ...
  </script>
</head>
<body>
  <div class="app-container">

    <!-- Top Nav with Alpine controlling which "page" we show -->
    <div class="top-nav">
      <div class="nav-section">
        <button :class="{ 'active': currentView==='calculator' }" @click="currentView='calculator'">Calculator</button>
        <button :class="{ 'active': currentView==='saved' }" @click="currentView='saved'; fetchCalcList()">Saved Calculators</button>
      </div>
      <div style="font-weight:bold;">Fleet Ownership Cost Analyzer</div>
    </div>

    <div class="content">
      <!-- PAGE: Calculator -->
      <div x-show="currentView==='calculator'" style="display:none;">
        
        <!-- (Your existing calculator UI) -->
        <div class="card" id="inputSection">
          <div style="margin-bottom:0.5rem;">
            <button id="leaseTab" class="tab" onclick="selectTab('lease')">Lease</button>
            <button id="financingTab" class="tab" onclick="selectTab('financing')">Financing</button>
            <button id="cashTab" class="tab" onclick="selectTab('cash')">Cash</button>
          </div>

          <!-- LEASE FORM -->
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
            <button style="margin-top:0.5rem;" onclick="saveCalculatorToApi()">Save Lease to /api/awesome</button>
          </div>

          <!-- FINANCING FORM -->
          <div id="financingForm" style="display:none;">
            <p style="color:#ccc;">Financing form here (todo)</p>
          </div>

          <!-- CASH FORM -->
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
            <button style="margin-top:0.5rem;" onclick="saveCalculatorToApi()">Save Cash to /api/awesome</button>
          </div>
        </div>

        <!-- RESULTS SECTION (Your existing results UI) -->
        <div class="card results-section hidden" id="results">
          <h2>Ownership Cost Breakdown</h2>
          <div id="monthlyPayment" class="cost-indicator"></div>
          <div id="totalUpfrontCost"></div>
          <div id="totalYearlyCost"></div>
          <div id="totalMonthlyCost"></div>
          <div id="leaseExtraInfo" style="display:none;">
            <h3 style="margin-top:1rem; margin-bottom:0.5rem;">Additional Lease Details</h3>
            <div id="aprEquivalent"></div>
            <div id="negotiatedPriceOutput"></div>
            <div id="netCapCostOutput"></div>
            <div id="residualValueOutput"></div>
            <div id="monthlyDepreciationOutput"></div>
            <div id="monthlyFinanceChargeOutput"></div>
            <div id="totalDepreciationOutput"></div>
            <div id="totalFinanceChargesOutput"></div>
            <div id="totalLeasePaymentsOutput"></div>
          </div>
        </div>
      </div>

      <!-- PAGE: Saved Calculators -->
      <div x-show="currentView==='saved'" style="display:none;">
        <div class="card">
          <h2>My Saved Calculators</h2>
          <button style="margin-bottom:0.5rem;" @click="fetchCalcList()">Refresh List</button>
          <ul style="list-style:none; padding:0; margin:0;">
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