<?php

namespace App\Actions\Employee;

use App\Models\Employee;
use Illuminate\Support\Facades\DB;

class UpdateEmployee
{
    /** @param array<string, mixed> $data */
    public function handle(Employee $employee, array $data): Employee
    {

        $employee = DB::transaction(function () use ($employee, $data): Employee {
            $employee->update([
                'name' => $data['name'],
                'phone' => $data['phone'],
                'birthdate' => $data['birthdate'] ?: null,
            ]);
            $employee->user->update([
                'name' => $data['name'],
                'email' => $data['email'],
            ]);

            return $employee;
        });

        return $employee;
    }
}
