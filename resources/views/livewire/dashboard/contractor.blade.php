<div>
    <h2 class="text-xl font-semibold">Contractor Dashboard</h2>

    <!-- Assigned Tasks -->
    <div class="mt-6">
        <h3 class="text-lg font-semibold">My Tasks</h3>
        @foreach ($tasks as $task)
            <div class="mb-4 border-b pb-4">
                <h4 class="text-lg font-semibold">{{ $task->name }}</h4>
                <p>Project: {{ $task->stage->project->name }}</p>
                <p>Stage: {{ $task->stage->name }}</p>
                <button wire:click="markAsCompleted({{ $task->id }})" class="text-green-500">Mark as Completed</button>
            </div>
        @endforeach
    </div>
</div>

