<?php

use App\Models\Customer;
use App\Models\Tenant;
use Illuminate\Auth\AuthenticationException;

test('only lists customers from the authenticated user tenant', function () {
    $user = actingAsUser();
    $own = createCustomer($user->tenant);
    createCustomer(Tenant::factory()->create());

    expect(Customer::pluck('id')->all())->toBe([$own->id]);
});

test('cannot find a customer from another tenant by id', function () {
    actingAsUser();
    $foreign = createCustomer(Tenant::factory()->create());

    expect(Customer::find($foreign->id))->toBeNull();
});

test('assigns the authenticated user tenant when creating', function () {
    $user = actingAsUser();

    $customer = Customer::create(['name' => 'Ana', 'phone' => '1155550000']);

    expect($customer->tenant_id)->toBe($user->tenant_id);
});

test('fails closed without an authenticated user', function () {
    expect(fn () => Customer::all())->toThrow(AuthenticationException::class);
    expect(fn () => Customer::create(['name' => 'Ana', 'phone' => '1155550000']))
        ->toThrow(AuthenticationException::class);
});
