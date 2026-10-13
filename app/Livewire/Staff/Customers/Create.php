<?php

namespace App\Livewire\Staff\Customers;

use App\Actions\Customer\CreateCustomer;
use App\Livewire\Forms\CustomerForm;
use App\Models\Customer;
use Flux\Flux;
use Livewire\Attributes\Authorize;
use Livewire\Component;

class Create extends Component
{
    public CustomerForm $form;

    #[Authorize('create', Customer::class)]
    public function save(CreateCustomer $action)
    {
        $action->handle($this->form->validate());
        $this->dispatch('customer-created');
        Flux::modal('create-customer')->close();
        Flux::toast(text: 'Cliente creado exitosamente', variant: 'success');
        $this->form->reset();
    }

    public function render()
    {
        return view('livewire.staff.customers.create');
    }
}
