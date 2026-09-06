<?php

namespace App\Livewire\Staff\Employees;

use App\Models\Employee;
use Livewire\Attributes\On;
use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        return view('livewire.staff.employees.index', [
            'employees' => Employee::with('user')->get(),
        ]);
    }

    #[On('employee-created')]
    public function refreshEmployees() {}
}
