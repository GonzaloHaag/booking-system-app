<?php

use App\Models\Service;
use App\Models\Tenant;
use Illuminate\Auth\AuthenticationException;

test('only lists services from the authenticated user tenant', function () {
    $user = actingAsUser();
    $own = createService($user->tenant);
    createService(Tenant::factory()->create());

    expect(Service::pluck('id')->all())->toBe([$own->id]);
});

test('cannot find a service from another tenant by id', function () {
    actingAsUser();
    $foreign = createService(Tenant::factory()->create());

    expect(Service::find($foreign->id))->toBeNull();
});

test('assigns the authenticated user tenant when creating', function () {
    $user = actingAsUser();
    $foreignTenant = Tenant::factory()->create();

    $service = Service::create([
        'name' => 'Corte',
        'price' => 5000,
        'duration_minutes' => 30,
        'tenant_id' => $foreignTenant->id,
    ]);

    expect($service->tenant_id)->toBe($user->tenant_id);
});

test('fails closed without an authenticated user', function () {
    expect(fn () => Service::all())->toThrow(AuthenticationException::class);
    expect(fn () => Service::create(['name' => 'Corte', 'price' => 5000, 'duration_minutes' => 30]))
        ->toThrow(AuthenticationException::class);
});
