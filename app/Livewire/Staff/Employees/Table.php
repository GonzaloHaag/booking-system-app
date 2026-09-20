<?php

namespace App\Livewire\Staff\Employees;

use App\Models\Employee;
use Livewire\Attributes\On;
use Livewire\Component;

class Table extends Component
{
    public function render()
    {
        return view('livewire.staff.employees.table', [
            'employees' => Employee::with('user')->get(),
        ]);
    }

    #[On('employee-created')]
    public function refreshEmployees() {}
}
