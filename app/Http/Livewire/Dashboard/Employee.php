<?php

// app/Http/Livewire/Dashboard/Employee.php

namespace App\Http\Livewire\Dashboard;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Project;

class Employee extends Component
{
    public $team;
    public $projects;

    public function mount()
    {
        $this->team = Auth::user()->currentTeam;
        $this->loadProjects();
    }

    public function loadProjects()
    {
        $this->projects = Project::where('team_id', $this->team->id)
            ->where('is_archived', false)
            ->whereHas('assignments', function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->get();
    }

    public function render()
    {
        return view('livewire.dashboard.employee');
    }
}

