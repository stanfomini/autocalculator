<?php
// app/Http/Livewire/SearchCustomers.php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Customer;

class SearchCustomers extends Component
{
    public $searchTerm = '';
    public $customers = [];
    public $selectedCustomer;

    public function updatedSearchTerm()
    {
        $this->customers = Customer::where('name', 'like', '%' . $this->searchTerm . '%')->get();
    }

    public function selectCustomer($customerId)
    {
        $this->selectedCustomer = Customer::find($customerId);
    }

    public function render()
    {
        return view('livewire.search-customers')->layout('layouts.app');
    }
}

