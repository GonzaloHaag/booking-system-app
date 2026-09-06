<?php

namespace App\Actions\Employee;

use App\Enums\UserRole;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CreateEmployee
{
    /** @param array<string, mixed> $data */
    public function handle(array $data): Employee
    {
        /** No asigno tenant id en user porque ya lo hace el booted de User.
         */
        $transaction = DB::transaction(function () use ($data): Employee {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'], /** El casts de User ya hashea la contraseña */
                'role' => UserRole::EMPLOYEE,
            ]);

            return Employee::create([
                'user_id' => $user->id,
                'name' => $data['name'],
                'phone' => $data['phone'],
                'birthdate' => $data['birthdate'] ?: null,
                'tenant_id' => $user->tenant_id, /** Asigno el tenant_id del usuario creado al empleado */
            ]);
        });

        return $transaction;
    }
}
