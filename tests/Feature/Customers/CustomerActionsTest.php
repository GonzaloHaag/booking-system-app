<?php

use App\Actions\Customer\CreateCustomer;
use App\Actions\Customer\DeleteCustomer;
use App\Actions\Customer\UpdateCustomer;

test('creates a customer for the authenticated user tenant', function () {
    $user = actingAsUser();

    $customer = app(CreateCustomer::class)->handle([
        'name' => 'Ana',
        'birthdate' => '1990-05-12',
        'phone' => '1155550000',
    ]);

    $this->assertDatabaseHas('customers', [
        'id' => $customer->id,
        'name' => 'Ana',
        'tenant_id' => $user->tenant_id,
        'user_id' => null,
    ]);
    expect($customer->birthdate->format('Y-m-d'))->toBe('1990-05-12');
});

test('stores an empty birthdate as null', function () {
    actingAsUser();

    $customer = app(CreateCustomer::class)->handle([
        'name' => 'Ana',
        'birthdate' => '',
        'phone' => '1155550000',
    ]);

    expect($customer->birthdate)->toBeNull();
});

test('updates a customer', function () {
    $user = actingAsUser();
    $customer = createCustomer($user->tenant);

    app(UpdateCustomer::class)->handle($customer, [
        'name' => 'Beatriz',
        'birthdate' => '',
        'phone' => '1166660000',
    ]);

    $this->assertDatabaseHas('customers', [
        'id' => $customer->id,
        'name' => 'Beatriz',
        'birthdate' => null,
        'phone' => '1166660000',
    ]);
});

test('deletes a customer', function () {
    $user = actingAsUser();
    $customer = createCustomer($user->tenant);

    app(DeleteCustomer::class)->handle($customer);

    $this->assertModelMissing($customer);
});
