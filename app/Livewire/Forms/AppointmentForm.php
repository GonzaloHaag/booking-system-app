<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class AppointmentForm extends Form
{
    #[Validate('required', message: 'El cliente es requerido.')]
    public string $customer_id = '';

    #[Validate('required', message: 'El servicio es requerido.')]
    public string $service_id = '';

    #[Validate('required', message: 'El empleado es requerido.')]
    public string $employee_id = '';

    #[Validate('required', message: 'La fecha es requerida.')]
    #[Validate('date', message: 'La fecha debe ser una fecha válida.')]
    public string $date = '';

    #[Validate('required', message: 'La hora de inicio es requerida.')]
    #[Validate('date_format:H:i', message: 'La hora de inicio debe tener el formato HH:MM.')]
    public string $start_time = '';

    #[Validate('max:250', message: 'Las notas no pueden tener más de 250 caracteres.')]
    public string $notes = '';
}
