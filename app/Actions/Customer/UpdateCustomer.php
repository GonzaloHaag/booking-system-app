<?php

namespace App\Actions\Customer;

use App\Models\Customer;

class UpdateCustomer
{
    /** @param array<string, mixed> $data */
    public function handle(Customer $customer, array $data): Customer
    {
        $customer->update([
            'name' => $data['name'],
            'birthdate' => $data['birthdate'] ?: null,
            'phone' => $data['phone'],
        ]);

        return $customer;
    }
}
