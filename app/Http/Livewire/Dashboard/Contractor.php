<?php

// app/Http/Livewire/Dashboard/Contractor.php

namespace App\Http\Livewire\Dashboard;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\ChecklistItem;

class Contractor extends Component
{
    public $tasks;

    public function mount()
    {
        $this->loadTasks();
    }

    public function loadTasks()
    {
        $this->tasks = ChecklistItem::where('assigned_to_user_id', Auth::id())
            ->where('is_completed', false)
            ->with('stage.project')
            ->get();
    }

    public function markAsCompleted($taskId)
    {
        $task = ChecklistItem::find($taskId);
        if ($task && $task->assigned_to_user_id == Auth::id()) {
            $task->is_completed = true;
            $task->completed_at = now();
            $task->save();

            $this->loadTasks();
        }
    }

    public function render()
    {
        return view('livewire.dashboard.contractor');
    }
}

