<?php

namespace App\Actions\Customer;

use App\Models\Customer;

class DeleteCustomer
{
    public function handle(Customer $customer): Customer
    {
        $customer->delete();

        return $customer;
    }
}
