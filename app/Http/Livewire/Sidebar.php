<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Sidebar extends Component
{

	    public $currentTab = 'add-customer';
     public $currentComponent = 'active-customers-and-projects';

    protected $listeners = [
        'showAddCustomerForm' => 'showAddCustomerForm',
        'showActiveCustomersAndProjects' => 'showActiveCustomersAndProjects',
    ];


	    public function showAddCustomerForm()
    {
        $this->currentComponent = 'add-customer-form';
    }

    public function showActiveCustomersAndProjects()
    {
        $this->currentComponent = 'active-customers-and-projects';
    }



    public function render()
    {
        return view('livewire.sidebar');
    }
}
