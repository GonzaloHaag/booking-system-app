<?php

namespace App\Actions\Customer;

use App\Enums\UserRole;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CreateCustomerAccount
{
    /** @param array<string, mixed> $data */
    public function handle(Customer $customer, array $data): Customer
    {

        if ($customer->user_id !== null) {
            throw new \Exception("El cliente {$customer->name} ya tiene una cuenta de usuario asociada.");
        }
        /** No asigno tenant id en user porque ya lo hace el booted de User.
         */
        $customer = DB::transaction(function () use ($customer, $data): Customer {
            $user = User::create([
                'name' => $customer->name,
                'email' => $data['email'],
                'password' => $data['password'], /** El casts de User ya hashea la contraseña */
                'role' => UserRole::CUSTOMER,
            ]);
            $customer->update([
                'user_id' => $user->id,
            ]);

            return $customer;
        });

        return $customer;
    }
}
