<?php

namespace App\Livewire\Staff\Customers;

use App\Models\Customer;
use Livewire\Attributes\On;
use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        return view('livewire.staff.customers.index', [
            'customers' => Customer::with('user')->get(),
        ]);
    }

    #[On('customer-created')]
    public function refreshCustomers() {}
}
