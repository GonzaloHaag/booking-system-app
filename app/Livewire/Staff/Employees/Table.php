<?php

namespace App\Livewire\Staff\Employees;

use App\Actions\Employee\DeleteEmployee;
use App\Models\Employee;
use Flux\Flux;
use Livewire\Attributes\On;
use Livewire\Component;

class Table extends Component
{
    public function delete(DeleteEmployee $action, int $id)
    {
        $employee = Employee::findOrFail($id); /** Esto pasa por el global scope que defini en el modelo */
        $this->authorize('delete', $employee); /** Policy */
        $action->handle($employee);
        Flux::toast(text: "Empleado {$employee->name} eliminado correctamente", variant: 'success');
        Flux::modal("delete-employee-{$id}")->close();
    }

    public function render()
    {
        return view('livewire.staff.employees.table', [
            'employees' => Employee::with('user')->orderBy('name')->get(),
        ]);
    }

    #[On('employee-created')]
    #[On('employee-updated')]
    public function refreshEmployees() {}
}
