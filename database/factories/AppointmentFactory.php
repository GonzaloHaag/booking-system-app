<?php

namespace Database\Factories;

use App\Enums\AppointmentStatus;
use App\Models\Appointment;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Service;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Appointment>
 */
class AppointmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * El empleado, el cliente y el servicio se crean en el mismo tenant que el turno
     * (las closures reciben los atributos ya resueltos, incluido `tenant_id`).
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'status' => AppointmentStatus::PENDING,
            'date' => fake()->dateTimeBetween('+1 day', '+1 month')->format('Y-m-d'),
            'start_time' => '09:00',
            'end_time' => '09:30',
            'tenant_id' => Tenant::factory(),
            'employee_id' => fn (array $attributes) => Employee::factory()->create([
                'tenant_id' => $attributes['tenant_id'],
                'user_id' => User::factory()->employee()->create(['tenant_id' => $attributes['tenant_id']]),
            ])->id,
            'customer_id' => fn (array $attributes) => Customer::factory()->create([
                'tenant_id' => $attributes['tenant_id'],
            ])->id,
            'service_id' => fn (array $attributes) => Service::factory()->create([
                'tenant_id' => $attributes['tenant_id'],
            ])->id,
        ];
    }
}
