<?php

// app/Http/Livewire/Dashboard/Admin.php

namespace App\Http\Livewire\Dashboard;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Customer;
use App\Models\Project;

class Admin extends Component
{
    public $team;
    public $customers;
    public $projects;

    public function mount()
    {
        $this->team = Auth::user()->currentTeam;
        $this->loadData();
    }

    public function loadData()
    {
        $this->customers = $this->team->customers()->where('is_active', true)->get();
        $this->projects = $this->team->projects()->where('is_archived', false)->get();
    }

    public function render()
    {
        return view('livewire.dashboard.admin');
    }
}

