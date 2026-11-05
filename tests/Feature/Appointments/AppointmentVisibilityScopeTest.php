<?php

use App\Enums\UserRole;
use App\Models\Appointment;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Tenant;

describe('visibleTo', function () {
    test('an admin sees every appointment of their tenant and none from another tenant', function () {
        $admin = actingAsUser(UserRole::ADMIN);
        $first = createAppointment($admin->tenant);
        $second = createAppointment($admin->tenant);
        createAppointment(Tenant::factory()->create());

        expect(Appointment::visibleTo($admin)->pluck('id')->all())
            ->toEqualCanonicalizing([$first->id, $second->id]);
    });

    test('an employee only sees their own appointments', function () {
        $tenant = Tenant::factory()->create();
        $employee = createEmployee($tenant);
        $coworker = createEmployee($tenant);
        $own = createAppointment($tenant, ['employee_id' => $employee->id]);
        createAppointment($tenant, ['employee_id' => $coworker->id]);
        $this->actingAs($employee->user);

        expect(Appointment::visibleTo($employee->user)->pluck('id')->all())->toBe([$own->id]);
    });

    test('a customer only sees their own appointments', function () {
        $tenant = Tenant::factory()->create();
        [$user, $own] = createCustomerUserWithAppointment($tenant);
        createCustomerUserWithAppointment($tenant);
        $this->actingAs($user);

        expect(Appointment::visibleTo($user)->pluck('id')->all())->toBe([$own->id]);
    });

    test('an employee does not see appointments from another tenant', function () {
        $employee = createEmployee(Tenant::factory()->create());
        createAppointment(Tenant::factory()->create());
        $this->actingAs($employee->user);

        expect(Appointment::visibleTo($employee->user)->count())->toBe(0);
    });
});

describe('user profile relations', function () {
    test('an employee user resolves their employee', function () {
        $employee = createEmployee(Tenant::factory()->create());
        $this->actingAs($employee->user);

        expect($employee->user->employee)->toBeInstanceOf(Employee::class)
            ->and($employee->user->employee->is($employee))->toBeTrue();
    });

    test('a customer user resolves their customer', function () {
        $user = createUser(['role' => UserRole::CUSTOMER]);
        $customer = createCustomer($user->tenant, ['user_id' => $user->id]);
        $this->actingAs($user);

        expect($user->customer)->toBeInstanceOf(Customer::class)
            ->and($user->customer->is($customer))->toBeTrue();
    });

    test('a customer user without a customer record resolves null', function () {
        $user = createUser(['role' => UserRole::CUSTOMER]);
        $this->actingAs($user);

        expect($user->customer)->toBeNull();
    });
});
