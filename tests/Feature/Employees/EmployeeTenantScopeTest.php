<?php

use App\Models\Employee;
use App\Models\Tenant;
use Illuminate\Auth\AuthenticationException;

test('only lists employees from the authenticated user tenant', function () {
    $user = actingAsUser();
    $own = createEmployee($user->tenant);
    createEmployee(Tenant::factory()->create());

    expect(Employee::pluck('id')->all())->toBe([$own->id]);
});

test('cannot find an employee from another tenant by id', function () {
    actingAsUser();
    $foreign = createEmployee(Tenant::factory()->create());

    expect(Employee::find($foreign->id))->toBeNull();
});

test('fails closed without an authenticated user', function () {
    expect(fn () => Employee::all())->toThrow(AuthenticationException::class);
});
