<?php

// app/Http/Livewire/Dashboard.php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Dashboard extends Component
{
    public $role;
    public $team;
    public $currentTab = 'add-customer';
     public $currentComponent = 'active-customers-and-projects';

    protected $listeners = [
        'showAddCustomerForm' => 'showAddCustomerForm',
        'showActiveCustomersAndProjects' => 'showActiveCustomersAndProjects',
    ];


    public function mount()
    {
        $this->team = Auth::user()->currentTeam;
if ($this->team) {
            // Access the role using the 'membership' relation
           
	
		 $this->role = $this->team->role;
//	$this->role = Auth::user()->currentTeam->role->get();
}
    }

	public function showAddCustomerForm()
    {
        $this->currentComponent = 'add-customer-form';
    }

    public function showActiveCustomersAndProjects()
    {
        $this->currentComponent = 'active-customers-and-projects';
    }

   
    public function render()
    {
	    return view('livewire.dashboard');
    }

}

