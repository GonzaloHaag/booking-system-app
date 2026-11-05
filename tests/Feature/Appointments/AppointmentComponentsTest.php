<?php

use App\Enums\AppointmentStatus;
use App\Enums\UserRole;
use App\Livewire\Staff\Appointments\Table;
use App\Models\Tenant;
use Livewire\Livewire;

test('an admin can open the appointments page and a customer cannot', function (UserRole $role, int $status) {
    actingAsUser($role);

    $this->get(route('staff.appointments'))->assertStatus($status);
})->with([
    'admin' => [UserRole::ADMIN, 200],
    'customer' => [UserRole::CUSTOMER, 403],
]);

test('an employee can open the appointments page', function () {
    $employee = createEmployee(Tenant::factory()->create());
    $this->actingAs($employee->user);

    $this->get(route('staff.appointments'))->assertOk();
});

describe('Table', function () {
    test('shows an empty state when there are no appointments', function () {
        actingAsUser();

        Livewire::test(Table::class)->assertSee('No hay turnos registrados.');
    });

    test('an admin sees every appointment of the tenant with its details', function () {
        $admin = actingAsUser();
        $customer = createCustomer($admin->tenant, ['name' => 'Cliente Uno']);
        $service = createService($admin->tenant, ['name' => 'Corte clasico']);
        createAppointment($admin->tenant, [
            'customer_id' => $customer->id,
            'service_id' => $service->id,
            'start_time' => '09:00',
            'end_time' => '09:30',
            'status' => AppointmentStatus::CONFIRMED,
        ]);
        createAppointment($admin->tenant, ['customer_id' => createCustomer($admin->tenant, ['name' => 'Cliente Dos'])->id]);

        Livewire::test(Table::class)
            ->assertSee('Cliente Uno')
            ->assertSee('Cliente Dos')
            ->assertSee('Corte clasico')
            ->assertSee('30 min.')
            ->assertSee('Confirmado');
    });

    test('an employee only sees their own appointments', function () {
        $tenant = Tenant::factory()->create();
        $employee = createEmployee($tenant);
        $coworker = createEmployee($tenant);
        createAppointment($tenant, [
            'employee_id' => $employee->id,
            'customer_id' => createCustomer($tenant, ['name' => 'Cliente Propio'])->id,
        ]);
        createAppointment($tenant, [
            'employee_id' => $coworker->id,
            'customer_id' => createCustomer($tenant, ['name' => 'Cliente Ajeno'])->id,
        ]);
        $this->actingAs($employee->user);

        Livewire::test(Table::class)
            ->assertSee('Cliente Propio')
            ->assertDontSee('Cliente Ajeno');
    });

    test('does not show appointments from another tenant', function () {
        actingAsUser();
        $foreignTenant = Tenant::factory()->create();
        createAppointment($foreignTenant, [
            'customer_id' => createCustomer($foreignTenant, ['name' => 'Cliente Otro Negocio'])->id,
        ]);

        Livewire::test(Table::class)->assertDontSee('Cliente Otro Negocio');
    });

    test('orders appointments by date and then by start time', function () {
        $admin = actingAsUser();
        $names = ['Beto Tarde' => ['2030-01-12', '09:00'], 'Ana Tarde' => ['2030-01-11', '11:00'], 'Carla Temprano' => ['2030-01-11', '09:00']];
        foreach ($names as $name => [$date, $start]) {
            createAppointment($admin->tenant, [
                'date' => $date,
                'start_time' => $start,
                'end_time' => '23:00',
                'customer_id' => createCustomer($admin->tenant, ['name' => $name])->id,
            ]);
        }

        Livewire::test(Table::class)->assertSeeInOrder(['Carla Temprano', 'Ana Tarde', 'Beto Tarde']);
    });

    test('still shows an appointment whose employee and service were deleted', function () {
        $admin = actingAsUser();
        $appointment = createAppointment($admin->tenant);
        $employeeName = $appointment->employee->name;
        $serviceName = $appointment->service->name;
        $appointment->employee->delete();
        $appointment->service->delete();

        Livewire::test(Table::class)
            ->assertSee($employeeName)
            ->assertSee($serviceName);
    });
});
