
<!-- resources/views/livewire/customer/dashboard.blade.php -->

<x-app-layout>
    <!-- Define the header slot for the customer dashboard -->
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Customer Dashboard') }}
        </h2>
    </x-slot>

    <!-- Main content section -->
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <!-- Customer dashboard content here -->
                <!-- Display project information, progress, etc. -->
            </div>
        </div>
    </div>



<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold">{{ $team->name }} - Project Dashboard</h1>
    <p class="mt-2">Welcome, {{ $customer->name }}</p>

    <!-- Progress Bar -->
    <div class="my-4">
        <div class="w-full bg-gray-200 rounded">
            <div class="bg-blue-600 text-xs leading-none py-1 text-center text-white rounded"
                 style="width: {{ $progress }}%">
                {{ $progress }}%
            </div>
        </div>
    </div>

    <!-- Stages and Checklist Items -->
    <div class="mt-6">
        @foreach ($stages as $stage)
            <div class="mb-4">
                <h2 class="text-xl font-semibold">{{ $stage->name }}</h2>
                <ul class="list-disc ml-6">
                    @foreach ($stage->checklistItems as $item)
                        <li class="{{ $item->is_completed ? 'line-through text-gray-500' : '' }}">
                            {{ $item->name }}
                        </li>
                    @endforeach
                </ul>
            </div>
        @endforeach
    </div>

    <!-- Company Information -->
    <div class="mt-8">
        <h2 class="text-xl font-semibold">Company Information</h2>
        <p>{{ $team->description }}</p>
        <!-- Add more company info as needed -->
    </div>
</div>
</x-app-layout>
