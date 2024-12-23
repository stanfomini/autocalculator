<?php 
// app/Http/Livewire/AddCustomerForm.php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Customer;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;

class AddCustomerForm extends Component
{
    public $name, $email, $phone_number, $address;
    public $customerExists = false, $existingCustomerId;
    public $isModalOpen = false;

        protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255|unique:customers,email',
        'phone_number' => 'required|string|max:15',
        'address' => 'nullable|string|max:255',
    ];

  
    public function submit()
    {
        $this->validate();

        $existingCustomer = Customer::where('email', $this->email)
            ->where('team_id', Auth::user()->currentTeam->id)
            ->first();

        if ($existingCustomer) {
            $this->customerExists = true;
            $this->existingCustomerId = $existingCustomer->id;
            return;
        }

        $customer = Customer::create([
            'name' => $this->name,
            'email' => $this->email,
            'phone_number' => $this->phone_number,
            'address' => $this->address,
            'team_id' => Auth::user()->currentTeam->id,
        ]);

        Project::create([
            'customer_id' => $customer->id,
            'team_id' => Auth::user()->currentTeam->id,
            'name' => 'New Project for ' . $customer->name,
            'status' => 'Pending',
        ]);

        $this->reset(['name', 'email', 'phone_number', 'address']);
        session()->flash('message', 'Customer and project added successfully!');
        $this->closeModal();
    }

    public function render()
    {
	return view('livewire.add-customer-form');
    }
}

