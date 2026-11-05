<?php

namespace App\Livewire\Staff\Appointments;

use App\Actions\Appointment\CreateAppointment;
use App\Livewire\Forms\AppointmentForm;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Service;
use Carbon\CarbonImmutable;
use Illuminate\View\View;
use Livewire\Component;

class Create extends Component
{
    public AppointmentForm $form;

    public function save(CreateAppointment $action)
    {
        $employee_id = $this->form->employee_id;
        $service_id = $this->form->service_id;
        $customer_id = $this->form->customer_id;
        $this->form->validate();
        $startsAt = CarbonImmutable::createFromFormat(
            'Y-m-d H:i',
            $this->form->date.' '.$this->form->start_time,
            'America/Argentina/Buenos_Aires',
        );
        $notes = $this->form->notes;
        $employee = Employee::findOrFail($employee_id);
        $service = Service::findOrFail($service_id);
        $customer = Customer::findOrFail($customer_id);

        $action->handle($employee, $service, $customer, $startsAt, $notes);
    }

    public function render(): View
    {
        return view('livewire.staff.appointments.create');
    }
}
