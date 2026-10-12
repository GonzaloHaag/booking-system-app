<?php

use App\Enums\UserRole;
use App\Models\Service;
use App\Models\Tenant;

test('staff can create services and customers cannot', function (UserRole $role, bool $allowed) {
    $user = createUser(['role' => $role]);

    expect($user->can('create', Service::class))->toBe($allowed);
})->with([
    'admin' => [UserRole::ADMIN, true],
    'employee' => [UserRole::EMPLOYEE, true],
    'customer' => [UserRole::CUSTOMER, false],
]);

test('staff can update and delete services from their own tenant', function (UserRole $role, bool $allowed) {
    $user = createUser(['role' => $role]);
    $service = createService($user->tenant);

    expect($user->can('update', $service))->toBe($allowed)
        ->and($user->can('delete', $service))->toBe($allowed);
})->with([
    'admin' => [UserRole::ADMIN, true],
    'employee' => [UserRole::EMPLOYEE, true],
    'customer' => [UserRole::CUSTOMER, false],
]);

test('staff cannot update or delete services from another tenant', function () {
    $user = createUser(['role' => UserRole::ADMIN]);
    $foreign = createService(Tenant::factory()->create());

    expect($user->can('update', $foreign))->toBeFalse()
        ->and($user->can('delete', $foreign))->toBeFalse();
});

test('any authenticated role can view services from its own tenant only', function (UserRole $role) {
    $user = createUser(['role' => $role]);
    $own = createService($user->tenant);
    $foreign = createService(Tenant::factory()->create());

    expect($user->can('viewAny', Service::class))->toBeTrue()
        ->and($user->can('view', $own))->toBeTrue()
        ->and($user->can('view', $foreign))->toBeFalse();
})->with([
    'admin' => [UserRole::ADMIN],
    'employee' => [UserRole::EMPLOYEE],
    'customer' => [UserRole::CUSTOMER],
]);
