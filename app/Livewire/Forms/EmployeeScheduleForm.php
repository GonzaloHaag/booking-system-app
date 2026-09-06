<?php

namespace App\Livewire\Forms;

use Livewire\Form;

class EmployeeScheduleForm extends Form
{
    public array $daysOfWeek = [
        /** Domingos por ahora no. */
        1 => [
            'name' => 'Lunes',
            'start_time' => '09:00',
            'end_time' => '18:00',
            'enabled' => true,
        ],
        2 => [
            'name' => 'Martes',
            'start_time' => '09:00',
            'end_time' => '18:00',
            'enabled' => true,
        ],
        3 => [
            'name' => 'Miércoles',
            'start_time' => '09:00',
            'end_time' => '18:00',
            'enabled' => true,
        ],
        4 => [
            'name' => 'Jueves',
            'start_time' => '09:00',
            'end_time' => '18:00',
            'enabled' => true,
        ],
        5 => [
            'name' => 'Viernes',
            'start_time' => '09:00',
            'end_time' => '18:00',
            'enabled' => true,
        ],
        6 => [
            'name' => 'Sábado',
            'start_time' => '09:00',
            'end_time' => '18:00',
            'enabled' => true,
        ],
    ];

    protected function rules(): array
    {
        return [
            'daysOfWeek' => 'required|array|min:1',
            'daysOfWeek.*.start_time' => 'required|date_format:H:i',
            'daysOfWeek.*.end_time' => 'required|date_format:H:i|',
            'daysOfWeek.*.enabled' => 'boolean',
        ];
    }
}
