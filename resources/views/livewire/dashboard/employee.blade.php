<div>
    <h2 class="text-xl font-semibold">Employee Dashboard</h2>

    <!-- Assigned Projects -->
    <div class="mt-6">
        <h3 class="text-lg font-semibold">My Projects</h3>
        @foreach ($projects as $project)
            <div class="mb-4 border-b pb-4">
                <h4 class="text-lg font-semibold">{{ $project->name }}</h4>
                <p>Customer: {{ $project->customer->name }}</p>
                <!-- Add more details as needed -->
                <button wire:click="viewProject({{ $project->id }})" class="text-blue-500">View Details</button>
            </div>
        @endforeach
    </div>
</div>

