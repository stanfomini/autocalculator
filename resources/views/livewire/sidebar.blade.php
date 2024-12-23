<div :class="$root.sidebarOpen ? 'translate-x-0 ease-out' : '-translate-x-full ease-in'" class="fixed z-30 inset-y-0 left-0 w-64 transition duration-300 transform bg-gray-800 overflow-y-auto md:relative md:translate-x-0">
    <div class="flex flex-col h-full">
        <!-- Sidebar Content -->
        <nav class="flex-1 px-2 py-4 space-y-2">
            <a href="#" class="block px-4 py-2 text-sm font-semibold text-white hover:bg-gray-700 rounded"
               wire:click.prevent="$emit('showActiveCustomersAndProjects')">Dashboard</a>
            <a href="#" class="block px-4 py-2 text-sm font-semibold text-white hover:bg-gray-700 rounded"
               wire:click.prevent="$emit('showAddCustomerForm')">Add Customer</a>
            <!-- Add more links as needed -->
        </nav>
    </div>
</div>
