<div>
    <!-- Active Customers -->
    <div class="mt-6">
        <h3 class="text-lg font-semibold">Active Customers</h3>
        <table class="w-full mt-4">
            <thead>
                <tr>
                    <th class="text-left">Name</th>
                    <th class="text-left">Phone</th>
                    <th class="text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($customers as $customer)
                    <tr class="border-t">
                        <td>{{ $customer->name }}</td>
                        <td>{{ $customer->phone_number }}</td>
                        <td>
                            <button wire:click="viewCustomer({{ $customer->id }})" class="text-blue-500">View</button>
                            <!-- Add more actions -->
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Active Projects -->
    <div class="mt-8">
        <h3 class="text-lg font-semibold">Active Projects</h3>
        <table class="w-full mt-4">
            <thead>
                <tr>
                    <th class="text-left">Project Name</th>
                    <th class="text-left">Customer</th>
                    <th class="text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($projects as $project)
                    <tr class="border-t">
                        <td>{{ $project->name }}</td>
                        <td>{{ $project->customer->name }}</td>
                        <td>
                            <button wire:click="viewProject({{ $project->id }})" class="text-blue-500">View</button>
                            <!-- Add more actions -->
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

