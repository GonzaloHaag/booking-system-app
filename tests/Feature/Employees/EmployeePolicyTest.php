<?php

use App\Enums\UserRole;
use App\Models\Employee;
use App\Models\Tenant;

test('only admins can manage employees', function (UserRole $role, bool $allowed) {
    $user = createUser(['role' => $role]);
    $employee = createEmployee($user->tenant);

    expect($user->can('viewAny', Employee::class))->toBe($allowed)
        ->and($user->can('create', Employee::class))->toBe($allowed)
        ->and($user->can('update', $employee))->toBe($allowed)
        ->and($user->can('delete', $employee))->toBe($allowed);
})->with([
    'admin' => [UserRole::ADMIN, true],
    'employee' => [UserRole::EMPLOYEE, false],
    'customer' => [UserRole::CUSTOMER, false],
]);

test('admins cannot update or delete employees from another tenant', function () {
    $user = createUser(['role' => UserRole::ADMIN]);
    $foreign = createEmployee(Tenant::factory()->create());

    expect($user->can('update', $foreign))->toBeFalse()
        ->and($user->can('delete', $foreign))->toBeFalse();
});
