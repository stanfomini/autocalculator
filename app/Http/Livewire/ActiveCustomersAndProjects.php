<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Customer;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;

class ActiveCustomersAndProjects extends Component
{
    
	public $team;
	public $customers;
    public $projects;

    public function mount()
    {

	    $this->team = Auth::user()->currentTeam;
        
    $this->customers = $this->team->customers()->where('is_active', true)->get();
        $this->projects = $this->team->projects()->where('is_archived', false)->get();
    }

    public function viewCustomer($customerId)
    {
        // Handle viewing customer details
    }

    public function viewProject($projectId)
    {
        // Handle viewing project details
    }

    public function render()
    {
        return view('livewire.active-customers-and-projects');
    }
}

