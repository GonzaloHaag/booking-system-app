<?php

use App\Enums\UserRole;
use App\Livewire\Staff\Customers\Create;
use App\Livewire\Staff\Customers\Table;
use App\Livewire\Staff\Customers\Update;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Livewire\Livewire;

test('staff can open the customers page and customers cannot', function (UserRole $role, int $status) {
    actingAsUser($role);

    $this->get(route('staff.customers'))->assertStatus($status);
})->with([
    'admin' => [UserRole::ADMIN, 200],
    'employee' => [UserRole::EMPLOYEE, 200],
    'customer' => [UserRole::CUSTOMER, 403],
]);

describe('Create', function () {
    test('creates a customer and dispatches the event', function () {
        $user = actingAsUser();

        Livewire::test(Create::class)
            ->set('form.name', 'Ana')
            ->set('form.phone', '1155550000')
            ->call('save')
            ->assertHasNoErrors()
            ->assertDispatched('customer-created');

        $this->assertDatabaseHas('customers', ['name' => 'Ana', 'tenant_id' => $user->tenant_id]);
    });

    test('validates the required fields', function () {
        actingAsUser();

        Livewire::test(Create::class)
            ->call('save')
            ->assertHasErrors([
                'form.name' => 'required',
                'form.phone' => 'required',
            ]);

        $this->assertDatabaseEmpty('customers');
    });

    test('validates the maximum lengths and the date', function () {
        actingAsUser();

        Livewire::test(Create::class)
            ->set('form.name', str_repeat('a', 101))
            ->set('form.phone', str_repeat('1', 21))
            ->set('form.birthdate', 'no-es-una-fecha')
            ->call('save')
            ->assertHasErrors([
                'form.name' => 'max',
                'form.phone' => 'max',
                'form.birthdate' => 'date',
            ]);
    });

    test('forbids customers', function () {
        actingAsUser(UserRole::CUSTOMER);

        Livewire::test(Create::class)
            ->set('form.name', 'Ana')
            ->set('form.phone', '1155550000')
            ->call('save')
            ->assertForbidden();

        $this->assertDatabaseEmpty('customers');
    });
});

describe('Update', function () {
    test('loads the customer into the form', function () {
        $user = actingAsUser();
        $customer = createCustomer($user->tenant, [
            'name' => 'Ana',
            'birthdate' => '1990-05-12',
            'phone' => '1155550000',
        ]);

        Livewire::test(Update::class, ['customer' => $customer])
            ->assertSet('form.name', 'Ana')
            ->assertSet('form.birthdate', '1990-05-12')
            ->assertSet('form.phone', '1155550000');
    });

    test('loads a customer without phone or birthdate', function () {
        $user = actingAsUser();
        $customer = createCustomer($user->tenant, ['birthdate' => null, 'phone' => null]);

        Livewire::test(Update::class, ['customer' => $customer])
            ->assertSet('form.birthdate', '')
            ->assertSet('form.phone', '');
    });

    test('updates the customer and dispatches the event', function () {
        $user = actingAsUser();
        $customer = createCustomer($user->tenant);

        Livewire::test(Update::class, ['customer' => $customer])
            ->set('form.name', 'Beatriz')
            ->call('save')
            ->assertHasNoErrors()
            ->assertDispatched('customer-updated');

        $this->assertDatabaseHas('customers', ['id' => $customer->id, 'name' => 'Beatriz']);
    });

    test('forbids customers', function () {
        $user = actingAsUser(UserRole::CUSTOMER);
        $customer = createCustomer($user->tenant, ['name' => 'Ana']);

        Livewire::test(Update::class, ['customer' => $customer])
            ->set('form.name', 'Beatriz')
            ->call('save')
            ->assertForbidden();

        $this->assertDatabaseHas('customers', ['id' => $customer->id, 'name' => 'Ana']);
    });

    test('forbids staff from another tenant', function () {
        actingAsUser();
        $foreign = createCustomer(Tenant::factory()->create(), ['name' => 'Ana']);

        Livewire::test(Update::class, ['customer' => $foreign])
            ->set('form.name', 'Beatriz')
            ->call('save')
            ->assertForbidden();

        $this->assertDatabaseHas('customers', ['id' => $foreign->id, 'name' => 'Ana']);
    });
});

describe('Table', function () {
    test('lists only the customers from the user tenant ordered by name', function () {
        $user = actingAsUser();
        createCustomer($user->tenant, ['name' => 'Beatriz']);
        createCustomer($user->tenant, ['name' => 'Ana']);
        createCustomer(Tenant::factory()->create(), ['name' => 'Carla']);

        Livewire::test(Table::class)
            ->assertSeeInOrder(['Ana', 'Beatriz'])
            ->assertDontSee('Carla');
    });

    test('shows the birthdate formatted and the account status', function () {
        $user = actingAsUser();
        createCustomer($user->tenant, ['birthdate' => '1990-05-12']);

        Livewire::test(Table::class)
            ->assertSee('12/05/1990')
            ->assertSee('Sin cuenta');
    });

    test('deletes a customer', function () {
        $user = actingAsUser();
        $customer = createCustomer($user->tenant);

        Livewire::test(Table::class)->call('delete', $customer->id);

        $this->assertModelMissing($customer);
    });

    test('forbids customers from deleting', function () {
        $user = actingAsUser(UserRole::CUSTOMER);
        $customer = createCustomer($user->tenant);

        Livewire::test(Table::class)
            ->call('delete', $customer->id)
            ->assertForbidden();

        $this->assertModelExists($customer);
    });

    test('cannot delete a customer from another tenant', function () {
        actingAsUser();
        $foreign = createCustomer(Tenant::factory()->create());

        expect(fn () => Livewire::test(Table::class)->call('delete', $foreign->id))
            ->toThrow(ModelNotFoundException::class);

        $this->assertModelExists($foreign);
    });
});
