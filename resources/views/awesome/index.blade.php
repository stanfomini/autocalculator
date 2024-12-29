<!DOCTYPE html>
<html lang="en" x-data="appData()">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
  <title>Fleet Ownership Cost Analyzer</title>
  <link rel="manifest" href="/manifest.json">
  <meta name="theme-color" content="#1e1e2f">

  <!-- Alpine for only toggling pages / storing calcList -->
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
    .cost-indicator {
      padding:0.5rem; border-radius:6px; display:inline-block; margin-bottom:1rem; font-weight:bold;
    }
    .green { background-color:#4caf50; color:#fff; }
    .yellow { background-color:#ffeb3b; color:#000; }
    .red { background-color:#f44336; color:#fff; }
    .flex-row { display:flex; gap:1rem; align-items:center; flex-wrap:wrap; }
    .small-label { color:#eee; font-size:0.9rem; font-weight:500; }
    .checkbox-label { display:flex; align-items:center; gap:0.5rem; }
  </style>

  <script>
    /////////////////////////////////
    // Service Worker
    /////////////////////////////////
    if('serviceWorker' in navigator){
      navigator.serviceWorker.register('/service-worker.js');
    }

    /////////////////////////////////
    // Alpine data
    /////////////////////////////////
    function appData(){
      return {
        currentView:'calculator',
        calcList:[],

        async fetchCalcList(){
          try{
            let resp=await fetch('/api/awesome');
            if(!resp.ok) throw new Error('Failed to fetch saved calculators');
            let data=await resp.json();
            this.calcList=data;
          } catch(e){
            console.error('fetchCalcList:',e);
            alert('Failed to fetch list. See console.');
          }
        },
        loadFromList(id){
          // calls the global function
          loadCalculatorFromList(id);
        }
      };
    }

    /////////////////////////////////
    // Global variables for the calculator
    /////////////////////////////////
    let selectedTab='lease';
    let lastChangedTaxField='percentage';
    let lastChangedResidualField='percentage';

    /////////////////////////////////
    // Tab switching
    /////////////////////////////////
    function selectTab(tabName){
      selectedTab=tabName;
      document.querySelectorAll('.tab').forEach(btn=>btn.classList.remove('active'));
      document.getElementById(tabName+'Tab').classList.add('active');
      updateFormVisibility();
      updateCalculations();
    }
    function updateFormVisibility(){
      document.getElementById('leaseForm').style.display=(selectedTab==='lease')?'block':'none';
      document.getElementById('financingForm').style.display=(selectedTab==='financing')?'block':'none';
      document.getElementById('cashForm').style.display=(selectedTab==='cash')?'block':'none';
    }

    /////////////////////////////////
    // Real-time sync logic
    /////////////////////////////////
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
      // Suppose we grab vehiclePrice - rebates => negotiated
      const vehiclePrice=parseFloat(document.getElementById('leaseVehiclePrice').value)||0;
      const rebates=parseFloat(document.getElementById('leaseRebatesAndDiscounts').value)||0;
      const negotiated=vehiclePrice-rebates;
      let taxPercentEl=document.getElementById('leaseTaxPercentage');
      let taxTotalEl=document.getElementById('leaseTaxTotal');

      if(negotiated<=0){
        if(lastChangedTaxField==='percentage'){
          taxTotalEl.value='0';
        } else {
          taxPercentEl.value='0';
        }
        return;
      }

      let taxPercent=parseFloat(taxPercentEl.value)||0;
      let taxTotal=parseFloat(taxTotalEl.value)||0;
      if(lastChangedTaxField==='percentage'){
        let computed=negotiated*(taxPercent/100);
        taxTotalEl.value=computed.toFixed(2);
      } else {
        // total => percentage
        let computedPercent=(taxTotal/negotiated)*100;
        taxPercentEl.value=computedPercent.toFixed(2);
      }
    }
    function syncLeaseResidual(){
      const vehiclePrice=parseFloat(document.getElementById('leaseVehiclePrice').value)||0;
      let resPercentEl=document.getElementById('residualValue');
      let resTotalEl=document.getElementById('residualValueTotal');
      let rPercent=parseFloat(resPercentEl.value)||0;
      let rTotal=parseFloat(resTotalEl.value)||0;

      if(vehiclePrice<=0){
        if(lastChangedResidualField==='percentage') resTotalEl.value='0';
        else resPercentEl.value='0';
        return;
      }

      if(lastChangedResidualField==='percentage'){
        // compute total
        let computed=vehiclePrice*(rPercent/100);
        resTotalEl.value=computed.toFixed(2);
      } else {
        // compute percent
        let computedPercent=(rTotal/vehiclePrice)*100;
        resPercentEl.value=computedPercent.toFixed(2);
      }
    }

    /////////////////////////////////
    // Calculation
    /////////////////////////////////
    function updateCalculations(){
      // gather all data from whichever tab is active
      let data={};
      if(selectedTab==='lease'){
        data.option='lease';
        const vehiclePrice=parseFloat(document.getElementById('leaseVehiclePrice').value)||0;
        const rebates=parseFloat(document.getElementById('leaseRebatesAndDiscounts').value)||0;
        data.negotiated=vehiclePrice-rebates;

        data.downPayment=parseFloat(document.getElementById('leaseDownPayment').value)||0;
        data.termMonths=parseInt(document.getElementById('leaseTermMonths').value)||36;

        // residual
        const resPercent=parseFloat(document.getElementById('residualValue').value)||0;
        const resTotal=parseFloat(document.getElementById('residualValueTotal').value)||0;
        data.lastChangedResidualField=lastChangedResidualField;
        data.vehiclePrice=vehiclePrice;
        data.resPercent=resPercent;
        data.resTotal=resTotal;

        data.moneyFactor=parseFloat(document.getElementById('moneyFactor').value)||0;
        // taxes
        data.lastChangedTaxField=lastChangedTaxField;
        const taxPercent=parseFloat(document.getElementById('leaseTaxPercentage').value)||0;
        const taxTotal=parseFloat(document.getElementById('leaseTaxTotal').value)||0;
        data.taxPercent=taxPercent; 
        data.taxTotal=taxTotal;

        data.capitalizeTaxes=document.getElementById('leaseAddTaxesToLease').checked;
        data.fees=parseFloat(document.getElementById('leaseAdditionalFees').value)||0;
        data.capitalizeFees=document.getElementById('leaseAddFeesToLease').checked;

        data.maintenance=parseFloat(document.getElementById('leaseMaintenanceCost').value)||0;
        data.monthlyInsurance=parseFloat(document.getElementById('leaseMonthlyInsurance').value)||0;
        data.monthlyFuel=parseFloat(document.getElementById('leaseMonthlyFuel').value)||0;

        data.option='lease';
        const result=calculateLeaseCostsAll(data);
        showLeaseResults(result);
      }
      else if(selectedTab==='cash'){
        data.option='cash';
        // do a full "cash" logic
        const vehiclePrice=parseFloat(document.getElementById('cashVehiclePrice').value)||0;
        const taxPercent=parseFloat(document.getElementById('cashTaxPercentage').value)||0;
        const taxesCapitalized=document.getElementById('cashAddTaxesToCash').checked;
        const fees=parseFloat(document.getElementById('cashAdditionalFees').value)||0;
        const feesCapitalized=document.getElementById('cashAddFeesToCash').checked;
        const maintenance=parseFloat(document.getElementById('cashMaintenanceCost').value)||0;
        const monthlyInsurance=parseFloat(document.getElementById('cashMonthlyInsurance').value)||0;
        const monthlyFuel=parseFloat(document.getElementById('cashMonthlyFuel').value)||0;
        let cData={
          vehiclePrice,
          taxPercent,
          taxesCapitalized,
          fees,
          feesCapitalized,
          maintenance,
          monthlyInsurance,
          monthlyFuel
        };
        let cResult=calculateCashCostsAll(cData);
        showCashResults(cResult);
      }
      else {
        // financing placeholder
        document.getElementById('results').classList.remove('hidden');
      }
    }

    function calculateLeaseCostsAll(d){
      // replicate your prior advanced logic
      const net= d.negotiated;
      // compute real taxes
      let actualTax=0;
      if(d.lastChangedTaxField==='percentage'){
        actualTax=net*(d.taxPercent/100);
      } else {
        actualTax=d.taxTotal;
      }
      let capCost=net;
      if(d.capitalizeTaxes) capCost+=actualTax;

      if(d.capitalizeFees) capCost+=d.fees;

      // residual
      let actualResidual=0;
      if(d.lastChangedResidualField==='percentage'){
        actualResidual= d.vehiclePrice*(d.resPercent/100);
      } else {
        actualResidual= d.resTotal;
      }

      let netCapCost=capCost-d.downPayment;
      let monthlyDep= (netCapCost-actualResidual)/ d.termMonths;
      let financeCharge= ((netCapCost+actualResidual)* d.moneyFactor);
      let monthlyPayment= monthlyDep+ financeCharge;

      // etc
      let totalUpfront= d.downPayment + (d.capitalizeTaxes?0:actualTax) + (d.capitalizeFees?0:d.fees);
      let monthlyTCO= monthlyPayment+(d.maintenance/12)+d.monthlyInsurance+ d.monthlyFuel;
      let annualTCO= (monthlyPayment*12)+ d.maintenance+( d.monthlyInsurance*12)+( d.monthlyFuel*12);

      return {
        monthlyPayment: monthlyPayment.toFixed(2),
        totalUpfrontCost: totalUpfront.toFixed(2),
        totalYearlyCost: annualTCO.toFixed(2),
        totalMonthlyCost: monthlyTCO.toFixed(2),
        aprEquivalent: (d.moneyFactor*2400).toFixed(2)+'%',
        negotiatedPrice: net.toFixed(2),
        netCapCost: netCapCost.toFixed(2),
        residualValue: actualResidual.toFixed(2),
        monthlyDepreciation: monthlyDep.toFixed(2),
        monthlyFinanceCharge: financeCharge.toFixed(2),
        totalDepreciation: (monthlyDep* d.termMonths).toFixed(2),
        totalFinanceCharges: (financeCharge* d.termMonths).toFixed(2),
        totalLeasePayments: (monthlyPayment* d.termMonths).toFixed(2),
      };
    }

    function showLeaseResults(r){
      // show result in UI
      document.getElementById('results').classList.remove('hidden');
      document.getElementById('monthlyPayment').innerText="Monthly Lease Payment: $"+ r.monthlyPayment;
      document.getElementById('totalUpfrontCost').innerText="Total Upfront Cost: $"+ r.totalUpfrontCost;
      document.getElementById('totalYearlyCost').innerText="Yearly Cost: $"+ r.totalYearlyCost;
      document.getElementById('totalMonthlyCost').innerText="Monthly Cost: $"+ r.totalMonthlyCost;

      document.getElementById('leaseExtraInfo').style.display='block';
      document.getElementById('aprEquivalent').innerText="APR eq: "+ r.aprEquivalent;
      document.getElementById('negotiatedPriceOutput').innerText="Negotiated: $"+ r.negotiatedPrice;
      document.getElementById('netCapCostOutput').innerText="Net Cap: $"+ r.netCapCost;
      document.getElementById('residualValueOutput').innerText="Residual: $"+ r.residualValue;
      document.getElementById('monthlyDepreciationOutput').innerText="Monthly Dep: $"+ r.monthlyDepreciation;
      document.getElementById('monthlyFinanceChargeOutput').innerText="Monthly Finance: $"+ r.monthlyFinanceCharge;
      document.getElementById('totalDepreciationOutput').innerText="Total Dep: $"+ r.totalDepreciation;
      document.getElementById('totalFinanceChargesOutput').innerText="Total Finance: $"+ r.totalFinanceCharges;
      document.getElementById('totalLeasePaymentsOutput').innerText="Total Payment: $"+ r.totalLeasePayments;

      // color coding monthlyPayment
      const payEl=document.getElementById('monthlyPayment');
      payEl.classList.remove('green','yellow','red');
      const numeric= parseFloat(r.monthlyPayment);
      if(numeric<400) payEl.classList.add('green');
      else if(numeric<700) payEl.classList.add('yellow');
      else payEl.classList.add('red');
    }

    function calculateCashCostsAll(d){
      // replicate your prior advanced logic
      let vPrice= d.vehiclePrice;
      let taxes= vPrice*(d.taxPercent/100);
      if(d.taxesCapitalized) { vPrice+=taxes; taxes=0; }
      if(d.feesCapitalized) { vPrice+= d.fees; d.fees=0; }
      let upfront= vPrice + taxes + d.fees;
      let annual= d.maintenance+( d.monthlyInsurance*12)+( d.monthlyFuel*12);
      let monthly= (d.maintenance/12)+ d.monthlyInsurance+ d.monthlyFuel;
      return {
        totalLoanCost: vPrice.toFixed(2),
        totalUpfrontCost: upfront.toFixed(2),
        totalYearlyCost: annual.toFixed(2),
        totalMonthlyCost: monthly.toFixed(2)
      };
    }

    function showCashResults(r){
      document.getElementById('results').classList.remove('hidden');
      document.getElementById('monthlyPayment').innerText="Cost: $"+ r.totalLoanCost;
      document.getElementById('totalUpfrontCost').innerText="Upfront: $"+ r.totalUpfrontCost;
      document.getElementById('totalYearlyCost').innerText="Annual: $"+ r.totalYearlyCost;
      document.getElementById('totalMonthlyCost').innerText="Monthly: $"+ r.totalMonthlyCost;
      document.getElementById('leaseExtraInfo').style.display='none';
      // color coding
      const payEl=document.getElementById('monthlyPayment');
      payEl.classList.remove('green','yellow','red');
    }

    /////////////////////////////////
    // Save & Load from API
    /////////////////////////////////
    async function saveCalculatorToApi(){
      let calc_type= selectedTab;
      // gather relevant fields
      let vehicle_price=0;
      if(calc_type==='lease'){
        vehicle_price= parseFloat(document.getElementById('leaseVehiclePrice').value)||0;
      } else if(calc_type==='cash'){
        vehicle_price= parseFloat(document.getElementById('cashVehiclePrice').value)||0;
      }
      // could gather more
      let payload={ calc_type, vehicle_price };
      try{
        let resp= await fetch('/api/awesome',{
          method:'POST',
          headers:{
            'Content-Type':'application/json',
            'Accept':'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
          },
          body: JSON.stringify(payload)
        });
        let out= await resp.json();
        if(!resp.ok){
          console.error('Save error:', out);
          alert('Failed to save. Check console.');
        } else {
          alert('Calculator saved successfully.');
        }
      } catch(err){
        console.error('Error saving calculator:',err);
        alert('Error. See console.');
      }
    }

    async function loadCalculatorFromList(id){
      try{
        let resp= await fetch('/api/awesome/'+id);
        if(!resp.ok) throw new Error('Failed to load record '+id);
        let calc=await resp.json();
        // interpret calc_type => selectedTab
        selectedTab= calc.calc_type||'lease';
        document.querySelectorAll('.tab').forEach(el=>el.classList.remove('active'));
        document.getElementById(selectedTab+'Tab').classList.add('active');
        updateFormVisibility();

        // fill fields
        if(selectedTab==='lease'){
          document.getElementById('leaseVehiclePrice').value= calc.vehicle_price||'';
          document.getElementById('leaseRebatesAndDiscounts').value= calc.rebates_and_discounts||'';
          document.getElementById('leaseDownPayment').value= calc.down_payment||'0';
          document.getElementById('leaseTermMonths').value= calc.term_months||'36';
          document.getElementById('residualValue').value= calc.residual_percent||'';
          document.getElementById('residualValueTotal').value= calc.residual_value||'';
          document.getElementById('moneyFactor').value= calc.money_factor||'';
          document.getElementById('leaseTaxPercentage').value= calc.tax_percent||'';
          document.getElementById('leaseTaxTotal').value= calc.tax_total||'';
          document.getElementById('leaseAddTaxesToLease').checked= calc.capitalize_taxes?true:false;
          document.getElementById('leaseAdditionalFees').value= calc.additional_fees||'';
          document.getElementById('leaseAddFeesToLease').checked= calc.capitalize_fees?true:false;
          document.getElementById('leaseMaintenanceCost').value= calc.maintenance_cost||'0';
          document.getElementById('leaseMonthlyInsurance').value= calc.monthly_insurance||'0';
          document.getElementById('leaseMonthlyFuel').value= calc.monthly_fuel||'0';
        } else if(selectedTab==='cash'){
          document.getElementById('cashVehiclePrice').value= calc.vehicle_price||'0';
          document.getElementById('cashTaxPercentage').value= calc.tax_percent||'0';
          document.getElementById('cashAddTaxesToCash').checked= calc.capitalize_taxes?true:false;
          document.getElementById('cashAdditionalFees').value= calc.additional_fees||'0';
          document.getElementById('cashAddFeesToCash').checked= calc.capitalize_fees?true:false;
          document.getElementById('cashMaintenanceCost').value= calc.maintenance_cost||'0';
          document.getElementById('cashMonthlyInsurance').value= calc.monthly_insurance||'0';
          document.getElementById('cashMonthlyFuel').value= calc.monthly_fuel||'0';
        } else {
          // financing not fully implemented
        }

        // recalc
        updateCalculations();
        // switch Alpine => "calculator" view
        document.querySelector('html').__x.$data.currentView='calculator';
      } catch(e){
        console.error('loadCalculatorFromList:', e);
        alert('Load error. See console.');
      }
    }
  </script>
</head>
<body>
  <div class="top-nav">
    <div class="nav-left">
      <button :class="{ 'active': currentView==='calculator' }" @click="currentView='calculator'">Calculator</button>
      <button :class="{ 'active': currentView==='saved' }" @click="currentView='saved'; fetchCalcList()">Saved Calculators</button>
    </div>
    <div style="font-weight:bold;">Fleet Ownership Cost Analyzer</div>
  </div>

  <div class="content">
    <!-- PAGE: Calculator -->
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

        <!-- FINANCING FORM (placeholder) -->
        <div id="financingForm" style="display:none;">
          <p style="color:#ccc;">Financing form (placeholder)</p>
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

    <!-- PAGE: Saved Calculators -->
    <div x-show="currentView==='saved'" style="display:none;">
      <div class="card">
        <h2>My Saved Calculators</h2>
        <button style="margin-bottom:0.5rem;" @click="fetchCalcList()">Refresh List</button>
        <ul style="list-style:none; padding:0; margin:0;">
          <template x-for="calc in calcList" :key="calc.id">
            <li style="margin:0.5rem 0;">
              <a href="#" style="color:#66afff; text-decoration:underline;"
                 @click.prevent="loadFromList(calc.id)">
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