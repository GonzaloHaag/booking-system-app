<?php

use App\Enums\UserRole;
use App\Livewire\Staff\Services\Create;
use App\Livewire\Staff\Services\Table;
use App\Livewire\Staff\Services\Update;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Livewire\Livewire;

test('staff can open the services page and customers cannot', function (UserRole $role, int $status) {
    actingAsUser($role);

    $this->get(route('staff.services'))->assertStatus($status);
})->with([
    'admin' => [UserRole::ADMIN, 200],
    'employee' => [UserRole::EMPLOYEE, 200],
    'customer' => [UserRole::CUSTOMER, 403],
]);

describe('Create', function () {
    test('creates a service and dispatches the event', function () {
        $user = actingAsUser();

        Livewire::test(Create::class)
            ->set('form.name', 'Corte')
            ->set('form.price', '5000')
            ->set('form.duration_minutes', '30')
            ->call('save')
            ->assertHasNoErrors()
            ->assertDispatched('service-created');

        $this->assertDatabaseHas('services', ['name' => 'Corte', 'tenant_id' => $user->tenant_id]);
    });

    test('validates the required fields', function () {
        actingAsUser();

        Livewire::test(Create::class)
            ->call('save')
            ->assertHasErrors([
                'form.name' => 'required',
                'form.price' => 'required',
                'form.duration_minutes' => 'required',
            ]);

        $this->assertDatabaseEmpty('services');
    });

    test('validates the minimum values', function () {
        actingAsUser();

        Livewire::test(Create::class)
            ->set('form.name', 'Corte')
            ->set('form.price', '-1')
            ->set('form.duration_minutes', '0')
            ->call('save')
            ->assertHasErrors([
                'form.price' => 'min',
                'form.duration_minutes' => 'min',
            ]);
    });

    test('forbids customers', function () {
        actingAsUser(UserRole::CUSTOMER);

        Livewire::test(Create::class)
            ->set('form.name', 'Corte')
            ->set('form.price', '5000')
            ->set('form.duration_minutes', '30')
            ->call('save')
            ->assertForbidden();

        $this->assertDatabaseEmpty('services');
    });
});

describe('Update', function () {
    test('loads the service into the form', function () {
        $user = actingAsUser();
        $service = createService($user->tenant, ['name' => 'Corte', 'duration_minutes' => 45]);

        Livewire::test(Update::class, ['service' => $service])
            ->assertSet('form.name', 'Corte')
            ->assertSet('form.duration_minutes', '45');
    });

    test('updates the service and dispatches the event', function () {
        $user = actingAsUser();
        $service = createService($user->tenant);

        Livewire::test(Update::class, ['service' => $service])
            ->set('form.name', 'Barba')
            ->call('save')
            ->assertHasNoErrors()
            ->assertDispatched('service-updated');

        $this->assertDatabaseHas('services', ['id' => $service->id, 'name' => 'Barba']);
    });

    test('forbids customers', function () {
        $user = actingAsUser(UserRole::CUSTOMER);
        $service = createService($user->tenant, ['name' => 'Corte']);

        Livewire::test(Update::class, ['service' => $service])
            ->set('form.name', 'Barba')
            ->call('save')
            ->assertForbidden();

        $this->assertDatabaseHas('services', ['id' => $service->id, 'name' => 'Corte']);
    });

    test('forbids staff from another tenant', function () {
        actingAsUser();
        $foreign = createService(Tenant::factory()->create(), ['name' => 'Corte']);

        Livewire::test(Update::class, ['service' => $foreign])
            ->set('form.name', 'Barba')
            ->call('save')
            ->assertForbidden();

        $this->assertDatabaseHas('services', ['id' => $foreign->id, 'name' => 'Corte']);
    });
});

describe('Table', function () {
    test('lists only the services from the user tenant', function () {
        $user = actingAsUser();
        createService($user->tenant, ['name' => 'Corte propio']);
        createService(Tenant::factory()->create(), ['name' => 'Corte ajeno']);

        Livewire::test(Table::class)
            ->assertSee('Corte propio')
            ->assertDontSee('Corte ajeno');
    });

    test('deletes a service', function () {
        $user = actingAsUser();
        $service = createService($user->tenant);

        Livewire::test(Table::class)->call('delete', $service->id);

        $this->assertModelMissing($service);
    });

    test('forbids customers from deleting', function () {
        $user = actingAsUser(UserRole::CUSTOMER);
        $service = createService($user->tenant);

        Livewire::test(Table::class)
            ->call('delete', $service->id)
            ->assertForbidden();

        $this->assertModelExists($service);
    });

    test('cannot delete a service from another tenant', function () {
        actingAsUser();
        $foreign = createService(Tenant::factory()->create());

        expect(fn () => Livewire::test(Table::class)->call('delete', $foreign->id))
            ->toThrow(ModelNotFoundException::class);

        $this->assertModelExists($foreign);
    });
});
