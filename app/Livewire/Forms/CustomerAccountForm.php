<?php

namespace App\Livewire\Forms;

use App\Concerns\PasswordValidationRules;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;
use Livewire\Form;

class CustomerAccountForm extends Form
{
    use PasswordValidationRules;

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
