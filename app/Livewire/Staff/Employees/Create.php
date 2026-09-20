<?php

namespace App\Livewire\Staff\Employees;

use App\Actions\Employee\CreateEmployee;
use App\Livewire\Forms\EmployeeForm;
use Flux\Flux;
use Livewire\Component;

class Create extends Component
{
    public EmployeeForm $form;

    public function save(CreateEmployee $action)
    {
        $action->handle($this->form->validate());
        $this->dispatch('employee-created');
        Flux::modal('create-employee')->close();
        Flux::toast(text: 'Empleado creado exitosamente', variant: 'success');
        $this->form->reset();
    }

    public function render()
    {
        return view('livewire.staff.employees.create');
    }
}
