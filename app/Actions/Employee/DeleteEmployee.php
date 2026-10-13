<?php

namespace App\Actions\Employee;

use App\Models\Employee;
use Illuminate\Support\Facades\DB;

class DeleteEmployee
{
    public function handle(Employee $employee): Employee
    {
        $employee = DB::transaction(function () use ($employee): Employee {
            $user = $employee->user;
            $employee->delete();
            $user->delete();

            return $employee;
        });

        return $employee;
    }
}
