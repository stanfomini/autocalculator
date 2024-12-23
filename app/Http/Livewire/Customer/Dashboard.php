<?php

namespace App\Http\Livewire\Customer;

use Livewire\Component;
use App\Models\Team;
use App\Models\Customer;
use App\Models\Project;

class Dashboard extends Component
{
    public $companySlug;
    public $customerPhone;
    public $team;
    public $customer;
    public $project;
    public $stages;
    public $progress;

    public function mount($companySlug, $customerPhone)
    {
        // Find the company by slug
        $this->team = Team::where('slug', $companySlug)->firstOrFail();

        // Find the customer by phone number within the team
        $this->customer = Customer::where('team_id', $this->team->id)
            ->where('phone_number', $customerPhone)
            ->firstOrFail();

        // Get the active project for the customer
        $this->project = $this->customer->projects()->where('is_archived', false)->first();

        // If there's an active project, get stages and calculate progress
        if ($this->project) {
            $this->stages = $this->project->stages()->with('checklistItems')->get();
            $this->calculateProgress();
        } else {
            $this->stages = collect();
            $this->progress = 0;
        }
    }

    public function calculateProgress()
    {
        $totalItems = 0;
        $completedItems = 0;

        foreach ($this->stages as $stage) {
            $items = $stage->checklistItems;
            $totalItems += $items->count();
            $completedItems += $items->where('is_completed', true)->count();
        }

        $this->progress = $totalItems > 0 ? round(($completedItems / $totalItems) * 100) : 0;
    }

    public function render()
    {
        return view('livewire.customer.dashboard');
    }
}
