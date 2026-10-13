<?php

namespace App\Livewire\Staff\Employees;

use App\Actions\EmployeeSchedule\CreateOrUpdateEmployeeSchedule;
use App\Livewire\Forms\EmployeeScheduleForm;
use App\Models\Employee;
use Flux\Flux;
use Livewire\Attributes\Authorize;
use Livewire\Component;

class Schedule extends Component
{
    public EmployeeScheduleForm $form;

    public Employee $employee;

    #[Authorize('update', 'employee')]
    public function save(CreateOrUpdateEmployeeSchedule $action)
    {
        $action->handle($this->employee, $this->form->validate());
        Flux::modal("schedule-employee-{$this->employee->id}")->close();
        Flux::toast(text: 'Horario guardado correctamente', variant: 'success');
    }

    public function mount(Employee $employee)
    {
        $this->employee = $employee;
        $employeeSchedulesByDay = $this->employee->schedules()->get()->keyBy('day_of_week');
        foreach ($this->form->daysOfWeek as $day => &$data) {
            $schedule = $employeeSchedulesByDay->get($day);

            $data['enabled'] = $schedule !== null;
            $data['start_time'] = $schedule->start_time ?? '09:00';
            $data['end_time'] = $schedule->end_time ?? '18:00';
        }
        unset($data);
    }

    public function render()
    {
        return view('livewire.staff.employees.schedule');
    }
}
