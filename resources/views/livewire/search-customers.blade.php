<!-- resources/views/livewire/search-customers.blade.php -->
<div>
    <input type="text" wire:model="searchTerm" placeholder="Search customers..." class="border rounded p-2">
    
    <ul class="mt-4">
        @foreach ($customers as $customer)
            <li wire:click="selectCustomer({{ $customer->id }})" class="p-2 bg-gray-200 mt-1 cursor-pointer">
                {{ $customer->name }}
            </li>
        @endforeach
    </ul>

    @if ($selectedCustomer)
        <!-- Display selected customer information here -->
        <div class="mt-4 p-4 border rounded">
            <h2 class="text-xl">{{ $selectedCustomer->name }}</h2>
            <p>Email: {{ $selectedCustomer->email }}</p>
            <p>Phone: {{ $selectedCustomer->phone_number }}</p>
            <p>Address: {{ $selectedCustomer->address }}</p>
        </div>
    @endif
</div>

