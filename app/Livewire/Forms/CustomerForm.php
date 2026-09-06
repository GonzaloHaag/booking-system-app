<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class CustomerForm extends Form
{
    #[Validate('required', message: 'El nombre es requerido.')]
    #[Validate('min:3', message: 'El nombre debe tener al menos 3 caracteres.')]
    #[Validate('max:100', message: 'El nombre no puede tener más de 100 caracteres.')]
    public string $name = '';

    #[Validate('nullable|date')]
    public string $birthdate = '';

    #[Validate('required', message: 'El teléfono es requerido.')]
    #[Validate('string', message: 'El teléfono debe ser una cadena de texto.')]
    #[Validate('max:20', message: 'El teléfono no puede tener más de 20 caracteres.')]
    public string $phone = '';
}
