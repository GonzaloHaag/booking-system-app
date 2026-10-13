<?php

namespace App\Livewire\Staff\Customers;

use App\Actions\Customer\CreateCustomerAccount;
use App\Livewire\Forms\CustomerAccountForm;
use App\Models\Customer;
use Flux\Flux;
use Livewire\Attributes\Authorize;
use Livewire\Component;

class CreateAccount extends Component
{
    public CustomerAccountForm $form;

    public Customer $customer;

    #[Authorize('update', 'customer')]
    public function save(CreateCustomerAccount $action)
    {
        $action->handle($this->customer, $this->form->validate());
        $this->dispatch('customer-account-created');
        Flux::modal("create-customer-account-{$this->customer->id}")->close();
        Flux::toast(text: 'Cuenta de cliente creada exitosamente', variant: 'success');
        $this->form->reset();
    }

    public function render()
    {
        return view('livewire.staff.customers.create-account');
    }
}
