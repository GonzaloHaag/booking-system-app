<?php

namespace App\Actions\Customer;

use App\Models\Customer;

class CreateCustomer
{
    /** @param array<string, mixed> $data */
    public function handle(array $data): Customer
    {
        return Customer::create([
            'name' => $data['name'],
            'birthdate' => $data['birthdate'] ?: null,
            'phone' => $data['phone'],
        ]);
    }
}
