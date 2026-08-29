<?php

namespace App\Actions\Customer;

use App\Models\Customer;

class CreateCustomer
{
    public function handle(array $data): Customer
    {
        return Customer::create([
            'name' => $data['name'],
            'birthdate' => $data['birthdate'] ?: null,
            'phone' => $data['phone'],
        ]);
    }
}
