<?php

use App\Actions\Appointment\CreateAppointment;
use App\Enums\AppointmentStatus;
use App\Exceptions\InvalidAppointmentTimeException;
use App\Models\Appointment;
use Carbon\CarbonImmutable;
use Illuminate\Auth\AuthenticationException;

beforeEach(function () {
    $this->travelTo(CarbonImmutable::parse('2030-01-10 08:00:00'));
});

test('creates a pending appointment with the end time calculated from the service duration', function () {
    $user = actingAsUser();
    $employee = createEmployee($user->tenant);
    $service = createService($user->tenant, ['duration_minutes' => 45]);
    $customer = createCustomer($user->tenant);

    $appointment = app(CreateAppointment::class)->handle(
        $employee,
        $service,
        $customer,
        CarbonImmutable::parse('2030-01-11 10:00:00'),
    );

    $fresh = Appointment::find($appointment->id);

    expect($fresh->status)->toBe(AppointmentStatus::PENDING)
        ->and($fresh->date->toDateString())->toBe('2030-01-11')
        ->and($fresh->start_time)->toStartWith('10:00')
        ->and($fresh->end_time)->toStartWith('10:45')
        ->and($fresh->employee_id)->toBe($employee->id)
        ->and($fresh->service_id)->toBe($service->id)
        ->and($fresh->customer_id)->toBe($customer->id)
        ->and($fresh->tenant_id)->toBe($user->tenant_id);
});

test('rejects an appointment that starts in the past', function () {
    $user = actingAsUser();

    expect(fn () => app(CreateAppointment::class)->handle(
        createEmployee($user->tenant),
        createService($user->tenant),
        createCustomer($user->tenant),
        CarbonImmutable::parse('2030-01-10 07:59:00'),
    ))->toThrow(InvalidAppointmentTimeException::class);

    $this->assertDatabaseEmpty('appointments');
});

test('accepts an appointment that starts in the future', function () {
    $user = actingAsUser();

    app(CreateAppointment::class)->handle(
        createEmployee($user->tenant),
        createService($user->tenant),
        createCustomer($user->tenant),
        CarbonImmutable::parse('2030-01-10 08:30:00'),
    );

    $this->assertDatabaseCount('appointments', 1);
});

test('fails closed without an authenticated user', function () {
    $tenant = actingAsUser()->tenant;
    $employee = createEmployee($tenant);
    $service = createService($tenant);
    $customer = createCustomer($tenant);
    auth()->logout();

    expect(fn () => app(CreateAppointment::class)->handle(
        $employee,
        $service,
        $customer,
        CarbonImmutable::parse('2030-01-11 10:00:00'),
    ))->toThrow(AuthenticationException::class);
});
