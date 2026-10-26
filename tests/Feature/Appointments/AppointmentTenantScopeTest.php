<?php

use App\Enums\AppointmentStatus;
use App\Models\Appointment;
use App\Models\Tenant;
use Illuminate\Auth\AuthenticationException;

test('only lists appointments from the authenticated user tenant', function () {
    $user = actingAsUser();
    $own = createAppointment($user->tenant);
    createAppointment(Tenant::factory()->create());

    expect(Appointment::pluck('id')->all())->toBe([$own->id]);
});

test('cannot find an appointment from another tenant by id', function () {
    actingAsUser();
    $foreign = createAppointment(Tenant::factory()->create());

    expect(Appointment::find($foreign->id))->toBeNull();
});

test('assigns the authenticated user tenant when creating', function () {
    $user = actingAsUser();
    $source = createAppointment($user->tenant);

    $appointment = Appointment::create([
        'date' => '2030-01-15',
        'start_time' => '10:00',
        'end_time' => '10:30',
        'employee_id' => $source->employee_id,
        'customer_id' => $source->customer_id,
        'service_id' => $source->service_id,
    ]);

    expect($appointment->tenant_id)->toBe($user->tenant_id);
});

test('ignores a tenant_id passed on creation', function () {
    $user = actingAsUser();
    $source = createAppointment($user->tenant);
    $otherTenant = Tenant::factory()->create();

    $appointment = Appointment::create([
        'tenant_id' => $otherTenant->id,
        'date' => '2030-01-15',
        'start_time' => '10:00',
        'end_time' => '10:30',
        'employee_id' => $source->employee_id,
        'customer_id' => $source->customer_id,
        'service_id' => $source->service_id,
    ]);

    expect($appointment->tenant_id)->toBe($user->tenant_id);
});

test('fails closed without an authenticated user', function () {
    expect(fn () => Appointment::all())->toThrow(AuthenticationException::class);
    expect(fn () => Appointment::create(['date' => '2030-01-15']))
        ->toThrow(AuthenticationException::class);
});

test('casts status to the enum and date to a Carbon instance', function () {
    $user = actingAsUser();
    $appointment = createAppointment($user->tenant, ['date' => '2030-01-15']);

    $fresh = Appointment::find($appointment->id);

    expect($fresh->status)->toBe(AppointmentStatus::PENDING)
        ->and($fresh->date->toDateString())->toBe('2030-01-15');
});

test('still shows the employee and service after they are soft deleted', function () {
    $user = actingAsUser();
    $appointment = createAppointment($user->tenant);
    $appointment->employee->delete();
    $appointment->service->delete();

    $fresh = Appointment::find($appointment->id);

    expect($fresh->employee)->not->toBeNull()
        ->and($fresh->employee->trashed())->toBeTrue()
        ->and($fresh->service)->not->toBeNull()
        ->and($fresh->service->trashed())->toBeTrue();
});

test('factory creates employee, customer and service in the appointment tenant', function () {
    $user = actingAsUser();
    $appointment = createAppointment($user->tenant);

    expect($appointment->employee->tenant_id)->toBe($user->tenant_id)
        ->and($appointment->employee->user->tenant_id)->toBe($user->tenant_id)
        ->and($appointment->customer->tenant_id)->toBe($user->tenant_id)
        ->and($appointment->service->tenant_id)->toBe($user->tenant_id);
});
