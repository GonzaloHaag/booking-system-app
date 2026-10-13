<?php

namespace App\Livewire\Staff\Customers;

use App\Actions\Customer\DeleteCustomer;
use App\Models\Customer;
use Flux\Flux;
use Livewire\Attributes\On;
use Livewire\Component;

class Table extends Component
{
    public function delete(DeleteCustomer $action, int $id)
    {
        $customer = Customer::findOrFail($id); /** Esto pasa por el global scope que defini en el modelo */
        $this->authorize('delete', $customer); /** Policy */
        $action->handle($customer);
        Flux::toast(text: "Cliente {$customer->name} eliminado correctamente", variant: 'success');
        Flux::modal("delete-customer-{$id}")->close();
    }

    public function render()
    {
        return view('livewire.staff.customers.table', [
            'customers' => Customer::with('user')->orderBy('name')->get(),
        ]);
    }

    #[On('customer-created')]
    #[On('customer-updated')]
    #[On('customer-account-created')]
    public function refreshCustomers() {}
}
