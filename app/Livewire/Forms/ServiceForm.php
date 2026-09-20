<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class ServiceForm extends Form
{
    #[Validate('required', message: 'El nombre es requerido.')]
    #[Validate('min:3', message: 'El nombre debe tener al menos 3 caracteres.')]
    #[Validate('max:100', message: 'El nombre no puede tener más de 100 caracteres.')]
    public string $name = '';

    #[Validate('max:255', message: 'La descripción no puede tener más de 255 caracteres.')]
    public string $description = '';

    #[Validate('required', message: 'El precio es requerido.')]
    #[Validate('numeric', message: 'El precio debe ser un número.')]
    #[Validate('min:0', message: 'El precio no puede ser negativo.')]
    public string $price = '';

    #[Validate('required', message: 'La duración es requerida.')]
    #[Validate('integer', message: 'La duración debe ser un número entero.')]
    #[Validate('min:1', message: 'La duración debe ser igual o mayor a 1 minuto.')]
    public string $duration_minutes = '';
}
