<?php

namespace App\Livewire\Staff\Customers;

use App\Actions\Customer\UpdateCustomer;
use App\Livewire\Forms\CustomerForm;
use App\Models\Customer;
use Flux\Flux;
use Livewire\Attributes\Authorize;
use Livewire\Component;

class Update extends Component
{
    public CustomerForm $form;

    public Customer $customer;

    #[Authorize('update', 'customer')]
    public function save(UpdateCustomer $action)
    {
        $action->handle($this->customer, $this->form->validate());
        $this->dispatch('customer-updated');
        Flux::modal("update-customer-{$this->customer->id}")->close();
        Flux::toast(text: 'Cliente actualizado correctamente', variant: 'success');
    }

    public function mount(Customer $customer)
    {
        $this->customer = $customer;
        $this->form->setCustomer($customer);
    }

    public function render()
    {
        return view('livewire.staff.customers.update');
    }
}
