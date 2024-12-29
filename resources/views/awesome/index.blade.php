<!DOCTYPE html>
<html lang="en" x-data="{ currentView:'calculator', calcList:[] }">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
  <title>Fleet Ownership Cost Analyzer</title>
  <link rel="manifest" href="/manifest.json">
  <meta name="theme-color" content="#1e1e2f">

  <!-- Alpine for top navigation only -->
  <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <style>
    body {
      margin:0;
      background:#1e1e2f;
      color:#eee;
      font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Oxygen,Ubuntu,Cantarell,"Open Sans","Helvetica Neue",sans-serif;
      font-size:16px;
    }
    .top-nav {
      background:#2a2a3c; color:#fff; display:flex; justify-content:space-between; align-items:center; padding:0.75rem 1rem;
    }
    .top-nav .nav-left button {
      background:#444; color:#bbb; border:none; padding:0.5rem 1rem; margin-right:0.5rem; border-radius:6px; cursor:pointer;
    }
    .top-nav .nav-left button.active {
      background:#007aff; color:#fff; font-weight:bold;
    }
    .content { padding:1rem; }
    .card {
      background:#2a2a3c; border-radius:12px; padding:1rem; margin-bottom:1rem; color:#fff;
    }
    input[type="number"], input[type="text"] {
      background:#333; color:#fff; border:1px solid #666; border-radius:6px; padding:0.75rem; font-size:1rem; width:100%;
    }
    .tab { background:#444; color:#bbb; padding:0.5rem 1rem; margin-right:0.5rem; border-radius:6px; cursor:pointer; border:none; }
    .tab.active { background:#007aff; color:#fff; }
    .hidden { display:none; }
    .cost-indicator { padding:0.5rem; border-radius:6px; display:inline-block; margin-bottom:1rem; font-weight:bold; }
    .green { background-color:#4caf50; color:#fff; }
    .yellow { background-color:#ffeb3b; color:#000; }
    .red { background-color:#f44336; color:#fff; }
    /* etc... */
  </style>

  <script>
    // Service Worker Registration
    if('serviceWorker' in navigator){
      navigator.serviceWorker.register('/service-worker.js');
    }

    //////////////////////////////////////////////////////////////////
    // OLD WORKING CALCULATOR LOGIC (LEASE, CASH, ETC.)
    //////////////////////////////////////////////////////////////////

    // Global variables for the old approach
    let selectedTab = 'lease';
    let lastChangedTaxField = 'percentage';
    let lastChangedResidualField = 'percentage';

    // The old tab logic
    function selectTab(tabName){
      selectedTab=tabName;
      document.querySelectorAll('.tab').forEach(el=>el.classList.remove('active'));
      document.getElementById(tabName+'Tab').classList.add('active');
      updateFormVisibility();
      updateCalculations();
    }
    function updateFormVisibility(){
      document.getElementById('leaseForm').style.display=(selectedTab==='lease'?'block':'none');
      document.getElementById('financingForm').style.display=(selectedTab==='financing'?'block':'none');
      document.getElementById('cashForm').style.display=(selectedTab==='cash'?'block':'none');
    }

    // The old synergy code for lease
    function leaseVehicleOrRebatesChanged(){
      syncLeaseTaxes();
      syncLeaseResidual();
      updateCalculations();
    }
    function leaseTaxPercentageChanged(){
      lastChangedTaxField='percentage';
      syncLeaseTaxes();
      updateCalculations();
    }
    function leaseTaxTotalChanged(){
      lastChangedTaxField='total';
      syncLeaseTaxes();
      updateCalculations();
    }
    function residualPercentageChanged(){
      lastChangedResidualField='percentage';
      syncLeaseResidual();
      updateCalculations();
    }
    function residualTotalChanged(){
      lastChangedResidualField='total';
      syncLeaseResidual();
      updateCalculations();
    }
    function syncLeaseTaxes(){
      // your old code for calculating taxes in lease...
      // set the relevant fields
    }
    function syncLeaseResidual(){
      // your old code for residual...
    }

    // The actual cost calculations
    function calculateLeaseCosts(data){
      // your old detailed logic that returns the needed fields
      return {
        monthlyPayment:'0.00',
        totalUpfrontCost:'0.00',
        totalYearlyCost:'0.00',
        totalMonthlyCost:'0.00',
        aprEquivalent:'0%',
        negotiatedPrice:'0.00',
        netCapCost:'0.00',
        residualValue:'0.00',
        monthlyDepreciation:'0.00',
        monthlyFinanceCharge:'0.00',
        totalDepreciation:'0.00',
        totalFinanceCharges:'0.00',
        totalLeasePayments:'0.00'
      };
    }
    function calculateCashCosts(data){
      // your old code for cash
      return {
        totalLoanCost:'0.00',
        totalUpfrontCost:'0.00',
        totalYearlyCost:'0.00',
        totalMonthlyCost:'0.00'
      };
    }

    function calculateGranularCosts(data){
      if(data.option==='lease'){
        return calculateLeaseCosts(data);
      } else if(data.option==='financing'){
        // placeholder
        return {
          monthlyPayment:'0.00',
          totalUpfrontCost:'0.00',
          totalYearlyCost:'0.00',
          totalMonthlyCost:'0.00'
        };
      } else {
        return calculateCashCosts(data);
      }
    }
    function updateCalculations(){
      // gather fields from whichever tab is selected
      let data={};
      if(selectedTab==='lease'){
        data.option='lease';
        // gather the existing fields e.g.:
        // data.vehiclePrice=parseFloat(document.getElementById('leaseVehiclePrice').value) || 0;
        // ...
      } else if(selectedTab==='financing'){
        data.option='financing';
      } else {
        data.option='cash';
        // gather e.g. data.vehiclePrice from "cashVehiclePrice" etc.
      }
      let result=calculateGranularCosts(data);
      updateUI(result);
    }

    function updateUI(calc){
      // e.g. set monthlyPayment, totalUpfrontCost, etc.
      // also do color coding
      document.getElementById('results').classList.remove('hidden');
    }

    //////////////////////////////////////////////////////////////////
    // SAVED CALCULATORS (GLOBAL FUNCTIONS)
    //////////////////////////////////////////////////////////////////

    // We'll store the fetched list in a global variable, so Alpine can show it:
    let calcListGlobal=[];

    async function fetchCalcList(){
      try {
        let resp=await fetch('/api/awesome');
        if(!resp.ok) throw new Error('Failed to fetch list');
        let data=await resp.json();
        calcListGlobal=data;
        // now we set the Alpine var
        document.querySelector('[x-data]').__x.$data.calcList=data;
      } catch(err){
        console.error('fetchCalcList error:',err);
        alert('Failed to fetch. See console.');
      }
    }

    async function loadCalculatorFromList(id){
      try {
        let resp=await fetch('/api/awesome/'+id);
        if(!resp.ok) throw new Error('Load error '+id);
        let calc=await resp.json();
        // interpret calc_type => selectedTab
        selectedTab=calc.calc_type||'lease';
        document.querySelectorAll('.tab').forEach(el=>el.classList.remove('active'));
        document.getElementById(selectedTab+'Tab').classList.add('active');
        updateFormVisibility();
        // fill fields e.g. if lease, set leaseVehiclePrice=calc.vehicle_price ...
        // then updateCalculations();
        updateCalculations();
        // switch Alpine page => "calculator"
        document.querySelector('[x-data]').__x.$data.currentView='calculator';
      } catch(err){
        console.error('loadCalculatorFromList error:',err);
        alert('Failed to load. See console.');
      }
    }

    async function saveCalculatorToApi(){
      let calc_type=selectedTab;
      let vehicle_price=0;
      if(calc_type==='lease'){
        vehicle_price=document.getElementById('leaseVehiclePrice').value||0;
      } else if(calc_type==='cash'){
        vehicle_price=document.getElementById('cashVehiclePrice').value||0;
      }
      // etc.
      const data={ calc_type, vehicle_price };
      try {
        let resp=await fetch('/api/awesome',{
          method:'POST',
          headers:{
            'Content-Type':'application/json',
            'Accept':'application/json',
            'X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content
          },
          body:JSON.stringify(data)
        });
        let result=await resp.json();
        if(!resp.ok){
          console.error('Save error:',result);
          alert('Failed to save. See console.');
        } else {
          alert('Saved to /api/awesome');
        }
      } catch(err){
        console.error('saveCalculatorToApi error:',err);
        alert('Error. See console.');
      }
    }
  </script>
</head>
<body>
  <!-- Top Nav with Alpine for page switching -->
  <div class="top-nav" x-data>
    <div class="nav-left">
      <button :class="{ 'active': currentView==='calculator' }"
              @click="currentView='calculator'">
        Calculator
      </button>
      <button :class="{ 'active': currentView==='saved' }"
              @click="currentView='saved'; fetchCalcList()">
        Saved Calculators
      </button>
    </div>
    <div style="font-weight:bold;">Fleet Ownership Cost Analyzer</div>
  </div>

  <div class="content" x-data="{ currentView:'calculator', calcList:[] }">
    <!-- CALCULATOR PAGE -->
    <div x-show="currentView==='calculator'" style="display:none;">

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
          <!-- more fields e.g. residualValue, etc. -->
          <button style="margin-top:1rem;" onclick="saveCalculatorToApi()">Save Lease to /api/awesome</button>
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
          <!-- more fields... -->
          <button style="margin-top:1rem;" onclick="saveCalculatorToApi()">Save Cash to /api/awesome</button>
        </div>
      </div>

      <!-- RESULTS SECTION -->
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

    <!-- SAVED CALCULATORS PAGE -->
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
</body>
</html>