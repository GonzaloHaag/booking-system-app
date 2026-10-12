<?php

use App\Actions\Service\CreateService;
use App\Actions\Service\DeleteService;
use App\Actions\Service\UpdateService;

test('creates a service for the authenticated user tenant', function () {
    $user = actingAsUser();

    $service = app(CreateService::class)->handle([
        'name' => 'Corte',
        'description' => 'Corte clásico',
        'price' => '5000.50',
        'duration_minutes' => '30',
    ]);

    $this->assertDatabaseHas('services', [
        'id' => $service->id,
        'name' => 'Corte',
        'tenant_id' => $user->tenant_id,
        'duration_minutes' => 30,
    ]);
});

test('stores an empty description as null', function () {
    actingAsUser();

    $service = app(CreateService::class)->handle([
        'name' => 'Corte',
        'description' => '',
        'price' => '5000',
        'duration_minutes' => '30',
    ]);

    expect($service->description)->toBeNull();
});

test('updates a service', function () {
    $user = actingAsUser();
    $service = createService($user->tenant);

    app(UpdateService::class)->handle($service, [
        'name' => 'Barba',
        'description' => '',
        'price' => '3000',
        'duration_minutes' => '20',
    ]);

    $this->assertDatabaseHas('services', [
        'id' => $service->id,
        'name' => 'Barba',
        'description' => null,
        'duration_minutes' => 20,
    ]);
});

test('deletes a service', function () {
    $user = actingAsUser();
    $service = createService($user->tenant);

    app(DeleteService::class)->handle($service);

    $this->assertModelMissing($service);
});
