<?php

use App\Enums\UserRole;
use App\Models\Customer;
use App\Models\Tenant;

test('staff can create customers and customers cannot', function (UserRole $role, bool $allowed) {
    $user = createUser(['role' => $role]);

    expect($user->can('create', Customer::class))->toBe($allowed);
})->with([
    'admin' => [UserRole::ADMIN, true],
    'employee' => [UserRole::EMPLOYEE, true],
    'customer' => [UserRole::CUSTOMER, false],
]);

test('staff can update and delete customers from their own tenant', function (UserRole $role, bool $allowed) {
    $user = createUser(['role' => $role]);
    $customer = createCustomer($user->tenant);

    expect($user->can('update', $customer))->toBe($allowed)
        ->and($user->can('delete', $customer))->toBe($allowed);
})->with([
    'admin' => [UserRole::ADMIN, true],
    'employee' => [UserRole::EMPLOYEE, true],
    'customer' => [UserRole::CUSTOMER, false],
]);

test('staff cannot update or delete customers from another tenant', function () {
    $user = createUser(['role' => UserRole::ADMIN]);
    $foreign = createCustomer(Tenant::factory()->create());

    expect($user->can('update', $foreign))->toBeFalse()
        ->and($user->can('delete', $foreign))->toBeFalse();
});
