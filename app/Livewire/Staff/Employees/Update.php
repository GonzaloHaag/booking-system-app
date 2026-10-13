<?php

namespace App\Livewire\Staff\Employees;

use App\Actions\Employee\UpdateEmployee;
use App\Livewire\Forms\EmployeeForm;
use App\Models\Employee;
use Flux\Flux;
use Livewire\Attributes\Authorize;
use Livewire\Component;

class Update extends Component
{
    public EmployeeForm $form;

    public Employee $employee;

    #[Authorize('update', 'employee')]
    public function save(UpdateEmployee $action)
    {
        $action->handle($this->employee, $this->form->validate());
        $this->dispatch('employee-updated');
        Flux::modal("update-employee-{$this->employee->id}")->close();
        Flux::toast(text: 'Empleado actualizado correctamente', variant: 'success');
    }

    public function mount(Employee $employee)
    {
        $this->employee = $employee;
        $this->form->setEmployee($employee);
    }

    public function render()
    {
        return view('livewire.staff.employees.update');
    }
}
