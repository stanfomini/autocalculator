
              <div>
	
                <!-- Livewire Component Content Here -->
                @if (Auth::user()->hasTeamRole(Auth::user()->currentTeam, 'admin'))
                    @livewire('dashboard.admin')
			<!-- Content Area -->
    <div class="p-6">
    @if ($currentComponent == 'add-customer-form')
        @livewire('add-customer-form')
    @elseif ($currentComponent == 'active-customers-and-projects')
        @livewire('active-customers-and-projects')
    @endif
</div>

                @elseif (Auth::user()->hasTeamRole(Auth::user()->currentTeam, 'employee'))
                    @livewire('dashboard.employee')
	

	<div class="p-6">
    @if ($currentComponent == 'add-customer-form')
        @livewire('add-customer-form')
    @elseif ($currentComponent == 'active-customers-and-projects')
        @livewire('active-customers-and-projects')
    @endif
</div>


                @elseif (Auth::user()->hasTeamRole(Auth::user()->currentTeam, 'contractor'))
                    @livewire('dashboard.contractor')
                @else
                    <p>Welcome to your dashboard!</p>
                @endif
		</div>
  

