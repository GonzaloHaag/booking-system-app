<?php

use App\Enums\UserRole;
use App\Livewire\Staff\Employees\Create;
use App\Livewire\Staff\Employees\Table;
use App\Livewire\Staff\Employees\Update;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Livewire\Livewire;

function createEmployeeForm($component, array $overrides = [])
{
    $values = [
        'name' => 'Ana',
        'phone' => '1155550000',
        'email' => 'ana@example.com',
        'password' => 'Str0ng-Passw0rd!',
        'password_confirmation' => 'Str0ng-Passw0rd!',
        ...$overrides,
    ];

    foreach ($values as $field => $value) {
        $component->set("form.$field", $value);
    }

    return $component;
}

test('only admins can open the employees page', function (UserRole $role, int $status) {
    actingAsUser($role);

    $this->get(route('staff.employees'))->assertStatus($status);
})->with([
    'admin' => [UserRole::ADMIN, 200],
    'employee' => [UserRole::EMPLOYEE, 403],
    'customer' => [UserRole::CUSTOMER, 403],
]);

test('only admins see the employees link in the sidebar', function (UserRole $role, bool $visible) {
    actingAsUser($role);

    $response = $this->get(route('staff.dashboard'));

    $visible
        ? $response->assertSee(route('staff.employees'))
        : $response->assertDontSee(route('staff.employees'));
})->with([
    'admin' => [UserRole::ADMIN, true],
    'employee' => [UserRole::EMPLOYEE, false],
]);

describe('Create', function () {
    test('creates an employee with its login and dispatches the event', function () {
        $admin = actingAsUser();

        createEmployeeForm(Livewire::test(Create::class))
            ->call('save')
            ->assertHasNoErrors()
            ->assertDispatched('employee-created');

        $this->assertDatabaseHas('employees', ['name' => 'Ana', 'tenant_id' => $admin->tenant_id]);
        $this->assertDatabaseHas('users', ['email' => 'ana@example.com', 'role' => UserRole::EMPLOYEE->value]);
    });

    test('validates the required fields', function () {
        actingAsUser();

        Livewire::test(Create::class)
            ->call('save')
            ->assertHasErrors([
                'form.name' => 'required',
                'form.phone' => 'required',
                'form.email' => 'required',
                'form.password' => 'required',
            ]);

        $this->assertDatabaseEmpty('employees');
    });

    test('rejects an email already used in the same tenant', function () {
        $admin = actingAsUser();
        $existing = createEmployee($admin->tenant);

        createEmployeeForm(Livewire::test(Create::class), ['email' => $existing->user->email])
            ->call('save')
            ->assertHasErrors(['form.email' => 'unique']);
    });

    test('accepts an email used in another tenant', function () {
        actingAsUser();
        $foreign = createEmployee(Tenant::factory()->create());

        createEmployeeForm(Livewire::test(Create::class), ['email' => $foreign->user->email])
            ->call('save')
            ->assertHasNoErrors();
    });

    test('requires the password confirmation', function () {
        actingAsUser();

        createEmployeeForm(Livewire::test(Create::class), ['password_confirmation' => 'otra'])
            ->call('save')
            ->assertHasErrors(['form.password' => 'confirmed']);
    });

    test('forbids employees and customers', function (UserRole $role) {
        actingAsUser($role);

        createEmployeeForm(Livewire::test(Create::class))
            ->call('save')
            ->assertForbidden();

        $this->assertDatabaseEmpty('employees');
    })->with([
        'employee' => UserRole::EMPLOYEE,
        'customer' => UserRole::CUSTOMER,
    ]);
});

describe('Update', function () {
    test('loads the employee into the form', function () {
        $admin = actingAsUser();
        $employee = createEmployee($admin->tenant, [
            'name' => 'Ana',
            'birthdate' => '1990-05-12',
            'phone' => '1155550000',
        ]);

        Livewire::test(Update::class, ['employee' => $employee])
            ->assertSet('form.name', 'Ana')
            ->assertSet('form.birthdate', '1990-05-12')
            ->assertSet('form.phone', '1155550000')
            ->assertSet('form.email', $employee->user->email);
    });

    test('loads an employee without phone or birthdate', function () {
        $admin = actingAsUser();
        $employee = createEmployee($admin->tenant, ['birthdate' => null, 'phone' => null]);

        Livewire::test(Update::class, ['employee' => $employee])
            ->assertSet('form.birthdate', '')
            ->assertSet('form.phone', '');
    });

    test('updates the employee without asking for a password', function () {
        $admin = actingAsUser();
        $employee = createEmployee($admin->tenant);

        Livewire::test(Update::class, ['employee' => $employee])
            ->set('form.name', 'Beatriz')
            ->call('save')
            ->assertHasNoErrors()
            ->assertDispatched('employee-updated');

        $this->assertDatabaseHas('employees', ['id' => $employee->id, 'name' => 'Beatriz']);
    });

    test('allows keeping its own email but not taking another one', function () {
        $admin = actingAsUser();
        $employee = createEmployee($admin->tenant);
        $other = createEmployee($admin->tenant);

        Livewire::test(Update::class, ['employee' => $employee])
            ->call('save')
            ->assertHasNoErrors();

        Livewire::test(Update::class, ['employee' => $employee])
            ->set('form.email', $other->user->email)
            ->call('save')
            ->assertHasErrors(['form.email' => 'unique']);
    });

    test('saves an empty birthdate', function () {
        $admin = actingAsUser();
        $employee = createEmployee($admin->tenant, ['birthdate' => '1990-05-12']);

        Livewire::test(Update::class, ['employee' => $employee])
            ->set('form.birthdate', '')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('employees', ['id' => $employee->id, 'birthdate' => null]);
    });

    test('forbids employees and customers', function (UserRole $role) {
        $user = actingAsUser($role);
        $employee = createEmployee($user->tenant, ['name' => 'Ana']);

        Livewire::test(Update::class, ['employee' => $employee])
            ->set('form.name', 'Beatriz')
            ->call('save')
            ->assertForbidden();

        $this->assertDatabaseHas('employees', ['id' => $employee->id, 'name' => 'Ana']);
    })->with([
        'employee' => UserRole::EMPLOYEE,
        'customer' => UserRole::CUSTOMER,
    ]);

    test('forbids admins from another tenant', function () {
        actingAsUser();
        $foreign = createEmployee(Tenant::factory()->create(), ['name' => 'Ana']);

        Livewire::test(Update::class, ['employee' => $foreign])
            ->set('form.name', 'Beatriz')
            ->call('save')
            ->assertForbidden();

        $this->assertDatabaseHas('employees', ['id' => $foreign->id, 'name' => 'Ana']);
    });
});

describe('Table', function () {
    test('lists only the employees from the user tenant ordered by name', function () {
        $admin = actingAsUser();
        createEmployee($admin->tenant, ['name' => 'Beatriz']);
        createEmployee($admin->tenant, ['name' => 'Ana']);
        createEmployee(Tenant::factory()->create(), ['name' => 'Carla']);

        Livewire::test(Table::class)
            ->assertSeeInOrder(['Ana', 'Beatriz'])
            ->assertDontSee('Carla');
    });

    test('shows the birthdate formatted and the login email', function () {
        $admin = actingAsUser();
        $employee = createEmployee($admin->tenant, ['birthdate' => '1990-05-12']);

        Livewire::test(Table::class)
            ->assertSee('12/05/1990')
            ->assertSee($employee->user->email);
    });

    test('deletes an employee and its user', function () {
        $admin = actingAsUser();
        $employee = createEmployee($admin->tenant);

        Livewire::test(Table::class)->call('delete', $employee->id);

        $this->assertDatabaseMissing('employees', ['id' => $employee->id]);
        $this->assertDatabaseMissing('users', ['id' => $employee->user_id]);
    });

    test('forbids employees and customers from deleting', function (UserRole $role) {
        $user = actingAsUser($role);
        $employee = createEmployee($user->tenant);

        Livewire::test(Table::class)
            ->call('delete', $employee->id)
            ->assertForbidden();

        $this->assertDatabaseHas('employees', ['id' => $employee->id]);
        $this->assertDatabaseHas('users', ['id' => $employee->user_id]);
    })->with([
        'employee' => UserRole::EMPLOYEE,
        'customer' => UserRole::CUSTOMER,
    ]);

    test('cannot delete an employee from another tenant', function () {
        actingAsUser();
        $foreign = createEmployee(Tenant::factory()->create());

        expect(fn () => Livewire::test(Table::class)->call('delete', $foreign->id))
            ->toThrow(ModelNotFoundException::class);

        $this->assertDatabaseHas('employees', ['id' => $foreign->id]);
    });
});
