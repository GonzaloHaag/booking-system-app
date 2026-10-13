<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'birthdate' => fake()->date(),
            'phone' => fake()->numerify('##########'),
            'tenant_id' => Tenant::factory(),
            'user_id' => User::factory()->employee(),
        ];
    }
}
