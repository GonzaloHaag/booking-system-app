<?php

namespace App\Livewire\Forms;

use App\Concerns\PasswordValidationRules;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Livewire\Form;

class EmployeeForm extends Form
{
    use PasswordValidationRules;

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

    public string $email = '';

    public string $password = '';

    public string $password_confirmation = '';

    /**
     * @return array<string, array<int, ValidationRule|array<mixed>|string>>
     */
    protected function rules(): array
    {
        return [
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->where('tenant_id', auth()->user()->tenant_id),
            ],
            'password' => $this->passwordRules(),
        ];
    }
}
