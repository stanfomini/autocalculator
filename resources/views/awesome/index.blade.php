<!DOCTYPE html>
<html lang="en" x-data="{ tab:'lease' }">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
  <title>Fleet Ownership Cost Analyzer</title>

  <!-- Dark style from before (#1e1e2f), with white text, plus your leasing functionality -->
  <link rel="manifest" href="/manifest.json">
  <meta name="theme-color" content="#1e1e2f">

  <!-- Alpine for tab switching only -->
  <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
  <!-- Optional CSRF if needed -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <style>
    body {
      margin: 0;
      font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen, Ubuntu, Cantarell, "Open Sans", "Helvetica Neue", sans-serif;
      background: #1e1e2f; /* Dark */
      color: #eee; /* Light text */
      font-size: 16px;
    }
    .app-container {
      display: flex;
      flex-direction: column;
      height: 100vh;
      overflow: auto;
    }
    .app-header {
      background: #2a2a3c;
      border-bottom: 1px solid #444;
      padding: 1rem;
      font-size: 1.25rem;
      font-weight: bold;
      text-align: center;
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
    .tab-bar {
      display: flex;
      gap: 0.5rem;
      justify-content: center;
    }
    .tab {
      padding: 0.5rem 1rem;
      cursor: pointer;
      border-radius: 6px;
      background: #444;
      font-size: 1rem;
      color: #bbb;
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
      color: #eee;
    }
    input[type="number"], input[type="text"] {
      width: 100%;
      padding: 0.75rem;
      font-size: 1rem;
      border: 1px solid #666;
      border-radius: 6px;
      background: #333;
      color: #fff;
    }
    .checkbox-label {
      display: flex;
      align-items: center;
      font-size: 1rem;
      gap: 0.5rem;
      color: #eee;
    }
    .small-label {
      font-weight: 500;
      font-size: 0.9rem;
      color: #eee;
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
    .green { background-color: #4caf50; color: #fff; }
    .yellow { background-color: #ffeb3b; color: #000; }
    .red { background-color: #f44336; color: #fff; }
    .results-section h2 {
      font-size: 1.25rem;
      margin-bottom: 1rem;
      font-weight: bold;
      color: #fff;
    }
    .results-section .detail {
      margin-bottom: 0.5rem;
      color: #fff;
    }
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
  </style>

  <script>
    // Service Worker registration
    if ('serviceWorker' in navigator) {
      navigator.serviceWorker.register('/service-worker.js');
    }

    let selectedTab = 'lease';
    let lastChangedTaxField = 'percentage';
    let lastChangedResidualField = 'percentage';

    function selectTab(tabName) {
      selectedTab = tabName;
      document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
      document.getElementById(tabName + 'Tab').classList.add('active');
      updateFormVisibility();
      updateCalculations();
    }

    function updateFormVisibility() {
      document.getElementById('leaseForm').style.display = (selectedTab === 'lease') ? 'block' : 'none';
      document.getElementById('financingForm').style.display = (selectedTab === 'financing') ? 'block' : 'none';
      document.getElementById('cashForm').style.display = (selectedTab === 'cash') ? 'block' : 'none';
    }

    function leaseTaxPercentageChanged() {
      lastChangedTaxField = 'percentage';
      syncLeaseTaxes();
      updateCalculations();
    }
    function leaseTaxTotalChanged() {
      lastChangedTaxField = 'total';
      syncLeaseTaxes();
      updateCalculations();
    }
    function residualPercentageChanged() {
      lastChangedResidualField = 'percentage';
      syncLeaseResidual();
      updateCalculations();
    }
    function residualTotalChanged() {
      lastChangedResidualField = 'total';
      syncLeaseResidual();
      updateCalculations();
    }
    function leaseVehicleOrRebatesChanged() {
      syncLeaseTaxes();
      syncLeaseResidual();
      updateCalculations();
    }

    function syncLeaseTaxes() {
      if (selectedTab !== 'lease') return;
      const vehiclePrice = parseFloat(document.getElementById('leaseVehiclePrice').value) || 0;
      const rebates = parseFloat(document.getElementById('leaseRebatesAndDiscounts').value) || 0;
      const negotiatedPrice = vehiclePrice - rebates;

      const taxPercentageInput = document.getElementById('leaseTaxPercentage');
      const taxTotalInput = document.getElementById('leaseTaxTotal');

      let taxPercentage = parseFloat(taxPercentageInput.value);
      let taxTotal = parseFloat(taxTotalInput.value);

      if (negotiatedPrice <= 0) {
        if (lastChangedTaxField === 'percentage') {
          taxTotalInput.value = '0.00';
        } else {
          taxPercentageInput.value = '0.00';
        }
        return;
      }

      if (lastChangedTaxField === 'percentage' && !isNaN(taxPercentage)) {
        const computedTotal = negotiatedPrice * (taxPercentage / 100);
        taxTotalInput.value = computedTotal.toFixed(2);
      } else if (lastChangedTaxField === 'total' && !isNaN(taxTotal)) {
        const computedPercentage = (taxTotal / negotiatedPrice) * 100;
        taxPercentageInput.value = computedPercentage.toFixed(2);
      }
    }

    function syncLeaseResidual() {
      if (selectedTab !== 'lease') return;
      const vehiclePrice = parseFloat(document.getElementById('leaseVehiclePrice').value) || 0;

      const residualPercentInput = document.getElementById('residualValue');
      const residualTotalInput = document.getElementById('residualValueTotal');

      let residualPercent = parseFloat(residualPercentInput.value);
      let residualTotal = parseFloat(residualTotalInput.value);

      if (vehiclePrice <= 0) {
        if (lastChangedResidualField === 'percentage') {
          residualTotalInput.value = '0.00';
        } else {
          residualPercentInput.value = '0.00';
        }
        return;
      }

      if (lastChangedResidualField === 'percentage' && !isNaN(residualPercent)) {
        const computedTotal = vehiclePrice * (residualPercent / 100);
        residualTotalInput.value = computedTotal.toFixed(2);
      } else if (lastChangedResidualField === 'total' && !isNaN(residualTotal)) {
        const computedPercentage = (residualTotal / vehiclePrice) * 100;
        residualPercentInput.value = computedPercentage.toFixed(2);
      }
    }

    function calculateGranularCosts(data) {
      if (data.option === 'lease') {
        return calculateLeaseCosts(data);
      } else if (data.option === 'financing') {
        // We can expand financing logic if needed
        return {monthlyPayment:"0.00",totalUpfrontCost:"0.00",totalYearlyCost:"0.00",totalMonthlyCost:"0.00"};
      } else {
        return calculateCashCosts(data);
      }
    }

    function calculateLeaseCosts(data) {
      const baseVehiclePrice = parseFloat(data.vehiclePrice) || 0;
      const rebatesAndDiscounts = parseFloat(data.rebatesAndDiscounts) || 0;
      const downPayment = parseFloat(data.downPayment) || 0;
      const residualValuePercentage = (parseFloat(data.residualValue) || 0) / 100;
      const residualValueTotal = parseFloat(data.residualValueTotal) || 0;
      const useResidualPercent = lastChangedResidualField === 'percentage';

      let residualValue = useResidualPercent ? baseVehiclePrice * residualValuePercentage : residualValueTotal;

      const moneyFactor = parseFloat(data.moneyFactor) || 0;
      const leaseTerm = parseInt(data.leaseTermMonths) || 1;
      const negotiatedPrice = baseVehiclePrice - rebatesAndDiscounts;
      const useTaxPercentage = lastChangedTaxField === 'percentage';

      let totalTaxAmount, taxPercentage;
      if (useTaxPercentage) {
        taxPercentage = (parseFloat(data.taxPercentage) || 0) / 100;
        totalTaxAmount = negotiatedPrice * taxPercentage;
      } else {
        totalTaxAmount = parseFloat(data.taxTotal) || 0;
        taxPercentage = (negotiatedPrice === 0) ? 0 : (totalTaxAmount / negotiatedPrice);
      }

      let additionalFees = parseFloat(data.additionalFees) || 0;
      let capCost = negotiatedPrice;
      if (data.addTaxesToLease) {
        capCost += totalTaxAmount;
        totalTaxAmount = 0;
      }
      if (data.addFeesToLease) {
        capCost += additionalFees;
        additionalFees = 0;
      }

      const netCapCost = capCost - downPayment;
      const monthlyDepreciation = (netCapCost - residualValue) / leaseTerm;
      const financeCharge = (netCapCost + residualValue) * moneyFactor;
      const monthlyPayment = monthlyDepreciation + financeCharge;

      const totalUpfrontCost = downPayment + additionalFees + totalTaxAmount;
      const totalYearlyCost = (monthlyPayment * 12) + data.maintenanceCost + (data.monthlyInsurance * 12) + (data.monthlyFuel * 12);
      const totalMonthlyCost = monthlyPayment + (data.maintenanceCost / 12) + data.monthlyInsurance + data.monthlyFuel;

      const aprEquivalent = (moneyFactor * 2400).toFixed(2) + '%';
      const totalDepreciation = (netCapCost - residualValue).toFixed(2);
      const totalFinanceCharges = (financeCharge * leaseTerm).toFixed(2);
      const totalLeasePayments = (monthlyPayment * leaseTerm).toFixed(2);

      return {
        monthlyPayment: monthlyPayment.toFixed(2),
        totalUpfrontCost: totalUpfrontCost.toFixed(2),
        totalYearlyCost: totalYearlyCost.toFixed(2),
        totalMonthlyCost: totalMonthlyCost.toFixed(2),
        aprEquivalent: aprEquivalent,
        negotiatedPrice: negotiatedPrice.toFixed(2),
        netCapCost: netCapCost.toFixed(2),
        residualValue: residualValue.toFixed(2),
        monthlyDepreciation: monthlyDepreciation.toFixed(2),
        monthlyFinanceCharge: financeCharge.toFixed(2),
        totalDepreciation: totalDepreciation,
        totalFinanceCharges: totalFinanceCharges,
        totalLeasePayments: totalLeasePayments
      };
    }

    function calculateCashCosts(data) {
      let vehiclePrice = parseFloat(data.vehiclePrice) || 0;
      const taxPercentage = (parseFloat(data.taxPercentage) || 0) / 100;
      let additionalFees = parseFloat(data.additionalFees) || 0;
      const maintenanceCost = parseFloat(data.maintenanceCost) || 0;
      const monthlyInsurance = parseFloat(data.monthlyInsurance) || 0;
      const monthlyFuel = parseFloat(data.monthlyFuel) || 0;

      let taxes = vehiclePrice * taxPercentage;
      if (data.addTaxesToCash) {
        vehiclePrice += taxes;
        taxes = 0;
      }
      if (data.addFeesToCash) {
        vehiclePrice += additionalFees;
        additionalFees = 0;
      }

      const totalUpfrontCost = vehiclePrice + additionalFees + taxes;
      const totalYearlyCost = maintenanceCost + (monthlyInsurance * 12) + (monthlyFuel * 12);
      const totalMonthlyCost = (maintenanceCost / 12) + monthlyInsurance + monthlyFuel;

      return {
        totalLoanCost: vehiclePrice.toFixed(2),
        totalUpfrontCost: totalUpfrontCost.toFixed(2),
        totalYearlyCost: totalYearlyCost.toFixed(2),
        totalMonthlyCost: totalMonthlyCost.toFixed(2)
      };
    }

    function updateCalculations() {
      let data = {};
      if (selectedTab === 'lease') {
        data = {
          vehiclePrice: parseFloat(document.getElementById('leaseVehiclePrice').value) || 0,
          rebatesAndDiscounts: parseFloat(document.getElementById('leaseRebatesAndDiscounts').value) || 0,
          downPayment: parseFloat(document.getElementById('leaseDownPayment').value) || 0,
          leaseTermMonths: parseInt(document.getElementById('leaseTermMonths').value) || 0,
          taxPercentage: parseFloat(document.getElementById('leaseTaxPercentage').value) || 0,
          taxTotal: parseFloat(document.getElementById('leaseTaxTotal').value) || 0,
          addTaxesToLease: document.getElementById('leaseAddTaxesToLease').checked,
          additionalFees: parseFloat(document.getElementById('leaseAdditionalFees').value) || 0,
          addFeesToLease: document.getElementById('leaseAddFeesToLease').checked,
          residualValue: parseFloat(document.getElementById('residualValue').value) || 0,
          residualValueTotal: parseFloat(document.getElementById('residualValueTotal').value) || 0,
          moneyFactor: parseFloat(document.getElementById('moneyFactor').value) || 0,
          maintenanceCost: parseFloat(document.getElementById('leaseMaintenanceCost').value) || 0,
          monthlyInsurance: parseFloat(document.getElementById('leaseMonthlyInsurance').value) || 0,
          monthlyFuel: parseFloat(document.getElementById('leaseMonthlyFuel').value) || 0,
          option: 'lease'
        };
      } else if (selectedTab === 'financing') {
        data = { option: 'financing' };
      } else {
        data = {
          vehiclePrice: parseFloat(document.getElementById('cashVehiclePrice').value) || 0,
          taxPercentage: parseFloat(document.getElementById('cashTaxPercentage').value) || 0,
          addTaxesToCash: document.getElementById('cashAddTaxesToCash').checked,
          additionalFees: parseFloat(document.getElementById('cashAdditionalFees').value) || 0,
          addFeesToCash: document.getElementById('cashAddFeesToCash').checked,
          maintenanceCost: parseFloat(document.getElementById('cashMaintenanceCost').value) || 0,
          monthlyInsurance: parseFloat(document.getElementById('cashMonthlyInsurance').value) || 0,
          monthlyFuel: parseFloat(document.getElementById('cashMonthlyFuel').value) || 0,
          option: 'cash'
        };
      }

      const calculations = calculateGranularCosts(data);
      updateUI(calculations);
    }

    function updateUI(calculations) {
      if (selectedTab === 'lease') {
        document.getElementById('monthlyPayment').innerText = `Monthly Lease Payment: $${calculations.monthlyPayment}`;
      } else if (selectedTab === 'financing') {
        document.getElementById('monthlyPayment').innerText = `Monthly Financing Payment: $${calculations.monthlyPayment}`;
      } else {
        document.getElementById('monthlyPayment').innerText = `Total Cost (Cash): $${calculations.totalLoanCost}`;
      }

      document.getElementById('totalUpfrontCost').innerText = `Total Upfront Cost: $${calculations.totalUpfrontCost}`;
      document.getElementById('totalYearlyCost').innerText = `Total Yearly All-Inclusive Cost: $${calculations.totalYearlyCost}`;
      document.getElementById('totalMonthlyCost').innerText = `Total Monthly Cost: $${calculations.totalMonthlyCost}`;

      if (selectedTab === 'lease') {
        document.getElementById('aprEquivalent').innerText = `APR Equivalent of Money Factor: ${calculations.aprEquivalent}`;
        document.getElementById('negotiatedPriceOutput').innerText = `Negotiated Price: $${calculations.negotiatedPrice}`;
        document.getElementById('netCapCostOutput').innerText = `Net Capitalized Cost: $${calculations.netCapCost}`;
        document.getElementById('residualValueOutput').innerText = `Residual Value: $${calculations.residualValue}`;
        document.getElementById('monthlyDepreciationOutput').innerText = `Monthly Depreciation: $${calculations.monthlyDepreciation}`;
        document.getElementById('monthlyFinanceChargeOutput').innerText = `Monthly Finance Charge: $${calculations.monthlyFinanceCharge}`;
        document.getElementById('totalDepreciationOutput').innerText = `Total Depreciation: $${calculations.totalDepreciation}`;
        document.getElementById('totalFinanceChargesOutput').innerText = `Total Finance Charges: $${calculations.totalFinanceCharges}`;
        document.getElementById('totalLeasePaymentsOutput').innerText = `Total Lease Payments: $${calculations.totalLeasePayments}`;
        document.getElementById('leaseExtraInfo').style.display = 'block';
      } else {
        document.getElementById('leaseExtraInfo').style.display = 'none';
      }

      const monthlyPaymentEl = document.getElementById('monthlyPayment');
      monthlyPaymentEl.className = 'cost-indicator';
      if ((selectedTab === 'lease' || selectedTab === 'financing') && calculations.monthlyPayment) {
        const monthlyPaymentValue = parseFloat(calculations.monthlyPayment);
        if (monthlyPaymentValue < 400) {
          monthlyPaymentEl.classList.add('green');
        } else if (monthlyPaymentValue < 700) {
          monthlyPaymentEl.classList.add('yellow');
        } else {
          monthlyPaymentEl.classList.add('red');
        }
      }

      document.getElementById('results').classList.remove('hidden');
    }

    document.addEventListener('DOMContentLoaded', () => {
      selectTab('lease'); // default to lease tab
    });
  </script>
</head>
<body>
  <div class="app-container">
    <div class="app-header">Fleet Ownership Cost Analyzer</div>
    <div class="content">

      <!-- Input Section -->
      <div class="card" id="inputSection">
        <div class="tab-bar">
          <div id="leaseTab" class="tab" @click="tab='lease'; selectTab('lease')">Lease</div>
          <div id="financingTab" class="tab" @click="tab='financing'; selectTab('financing')">Financing</div>
          <div id="cashTab" class="tab" @click="tab='cash'; selectTab('cash')">Cash</div>
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
          <p style="color:#ccc;">Financing form (to be implemented if needed)</p>
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

      <!-- Results Section -->
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
  </div>
</body>
</html>