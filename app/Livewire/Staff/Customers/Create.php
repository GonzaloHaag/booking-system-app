<?php

namespace App\Livewire\Staff\Customers;

use App\Actions\Customer\CreateCustomer;
use App\Livewire\Forms\CustomerForm;
use Flux\Flux;
use Livewire\Component;

class Create extends Component
{
    public CustomerForm $form;

    public function save(CreateCustomer $action)
    {
        $action->handle($this->form->validate());
        $this->form->reset();
        $this->dispatch('customer-created');
        Flux::modal('create-customer')->close();
        Flux::toast(text: 'Cliente creado exitosamente', variant: 'success');
    }

    public function render()
    {
        return view('livewire.staff.customers.create');
    }
}
