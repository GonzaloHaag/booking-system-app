<?php

use App\Enums\UserRole;
use App\Models\Appointment;
use App\Models\Tenant;
use App\Models\User;

/**
 * Crea un usuario customer con su Customer vinculado y un turno de ese cliente.
 *
 * @return array{0: User, 1: Appointment}
 */
function createCustomerUserWithAppointment(Tenant $tenant): array
{
    $user = createUser(['role' => UserRole::CUSTOMER], $tenant);
    $customer = createCustomer($tenant, ['user_id' => $user->id]);

    return [$user, createAppointment($tenant, ['customer_id' => $customer->id])];
}

test('any authenticated role can view any appointments', function (UserRole $role) {
    $user = createUser(['role' => $role]);

    expect($user->can('viewAny', Appointment::class))->toBeTrue();
})->with([
    'admin' => [UserRole::ADMIN],
    'employee' => [UserRole::EMPLOYEE],
    'customer' => [UserRole::CUSTOMER],
]);

test('admin and customer can create appointments for an employee of their tenant', function (UserRole $role) {
    $user = createUser(['role' => $role]);
    $employee = createEmployee($user->tenant);

    expect($user->can('create', [Appointment::class, $employee]))->toBeTrue();
})->with([
    'admin' => [UserRole::ADMIN],
    'customer' => [UserRole::CUSTOMER],
]);

test('an employee can only create appointments for themselves', function () {
    $tenant = Tenant::factory()->create();
    $employee = createEmployee($tenant);
    $coworker = createEmployee($tenant);

    expect($employee->user->can('create', [Appointment::class, $employee]))->toBeTrue()
        ->and($employee->user->can('create', [Appointment::class, $coworker]))->toBeFalse();
});

test('nobody can create appointments for an employee of another tenant', function (UserRole $role) {
    $user = createUser(['role' => $role]);
    $foreignEmployee = createEmployee(Tenant::factory()->create());

    expect($user->can('create', [Appointment::class, $foreignEmployee]))->toBeFalse();
})->with([
    'admin' => [UserRole::ADMIN],
    'employee' => [UserRole::EMPLOYEE],
    'customer' => [UserRole::CUSTOMER],
]);

test('an admin can view, update and cancel any appointment from their own tenant', function () {
    $admin = createUser(['role' => UserRole::ADMIN]);
    $appointment = createAppointment($admin->tenant);
    $this->actingAs($admin);

    expect($admin->can('view', $appointment))->toBeTrue()
        ->and($admin->can('update', $appointment))->toBeTrue()
        ->and($admin->can('cancel', $appointment))->toBeTrue();
});

test('an employee can view, update and cancel their own appointments', function () {
    $employee = createEmployee(Tenant::factory()->create());
    $appointment = createAppointment($employee->tenant, ['employee_id' => $employee->id]);
    $this->actingAs($employee->user);

    expect($employee->user->can('view', $appointment))->toBeTrue()
        ->and($employee->user->can('update', $appointment))->toBeTrue()
        ->and($employee->user->can('cancel', $appointment))->toBeTrue();
});

test('an employee cannot view, update or cancel the appointment of a coworker', function () {
    $tenant = Tenant::factory()->create();
    $employee = createEmployee($tenant);
    $coworker = createEmployee($tenant);
    $coworkerAppointment = createAppointment($tenant, ['employee_id' => $coworker->id]);
    $this->actingAs($employee->user);

    expect($employee->user->can('view', $coworkerAppointment))->toBeFalse()
        ->and($employee->user->can('update', $coworkerAppointment))->toBeFalse()
        ->and($employee->user->can('cancel', $coworkerAppointment))->toBeFalse();
});

test('staff cannot view, update or cancel appointments from another tenant', function (UserRole $role) {
    $user = createUser(['role' => $role]);
    $foreign = createAppointment(Tenant::factory()->create());
    $this->actingAs($user);

    expect($user->can('view', $foreign))->toBeFalse()
        ->and($user->can('update', $foreign))->toBeFalse()
        ->and($user->can('cancel', $foreign))->toBeFalse();
})->with([
    'admin' => [UserRole::ADMIN],
    'employee' => [UserRole::EMPLOYEE],
]);

test('a customer can view and cancel their own appointment but not update it', function () {
    [$user, $appointment] = createCustomerUserWithAppointment(Tenant::factory()->create());
    $this->actingAs($user);

    expect($user->can('view', $appointment))->toBeTrue()
        ->and($user->can('cancel', $appointment))->toBeTrue()
        ->and($user->can('update', $appointment))->toBeFalse();
});

test('a customer cannot view or cancel the appointment of another customer in the same tenant', function () {
    $tenant = Tenant::factory()->create();
    [$user] = createCustomerUserWithAppointment($tenant);
    [, $otherAppointment] = createCustomerUserWithAppointment($tenant);
    $this->actingAs($user);

    expect($user->can('view', $otherAppointment))->toBeFalse()
        ->and($user->can('cancel', $otherAppointment))->toBeFalse()
        ->and($user->can('update', $otherAppointment))->toBeFalse();
});

test('a customer cannot view or cancel an appointment from another tenant', function () {
    [$user] = createCustomerUserWithAppointment(Tenant::factory()->create());
    $foreign = createAppointment(Tenant::factory()->create());
    $this->actingAs($user);

    expect($user->can('view', $foreign))->toBeFalse()
        ->and($user->can('cancel', $foreign))->toBeFalse();
});

test('a customer cannot access the appointment of a customer without an account', function () {
    $user = createUser(['role' => UserRole::CUSTOMER]);
    $customer = createCustomer($user->tenant);
    $appointment = createAppointment($user->tenant, ['customer_id' => $customer->id]);
    $this->actingAs($user);

    expect($user->can('view', $appointment))->toBeFalse()
        ->and($user->can('cancel', $appointment))->toBeFalse();
});

test('nobody can delete an appointment', function (UserRole $role) {
    $user = createUser(['role' => $role]);
    $appointment = createAppointment($user->tenant);
    $this->actingAs($user);

    expect($user->can('delete', $appointment))->toBeFalse();
})->with([
    'admin' => [UserRole::ADMIN],
    'employee' => [UserRole::EMPLOYEE],
    'customer' => [UserRole::CUSTOMER],
]);
