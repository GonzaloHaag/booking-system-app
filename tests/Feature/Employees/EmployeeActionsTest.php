<?php

use App\Actions\Employee\CreateEmployee;
use App\Actions\Employee\DeleteEmployee;
use App\Actions\Employee\UpdateEmployee;
use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Hash;

function employeeData(array $overrides = []): array
{
    return [
        'name' => 'Ana',
        'birthdate' => '1990-05-12',
        'phone' => '1155550000',
        'email' => 'ana@example.com',
        'password' => 'Str0ng-Passw0rd!',
        ...$overrides,
    ];
}

describe('CreateEmployee', function () {
    test('creates the employee together with its user', function () {
        $admin = actingAsUser();

        $employee = app(CreateEmployee::class)->handle(employeeData());

        $this->assertDatabaseHas('employees', [
            'id' => $employee->id,
            'name' => 'Ana',
            'phone' => '1155550000',
            'tenant_id' => $admin->tenant_id,
        ]);
        $this->assertDatabaseHas('users', [
            'id' => $employee->user_id,
            'email' => 'ana@example.com',
            'role' => UserRole::EMPLOYEE->value,
        ]);
    });

    test('keeps the employee tenant equal to its user tenant', function () {
        actingAsUser();

        $employee = app(CreateEmployee::class)->handle(employeeData());

        expect($employee->tenant_id)->toBe($employee->user->tenant_id);
    });

    test('hashes the password', function () {
        actingAsUser();

        $employee = app(CreateEmployee::class)->handle(employeeData());

        expect($employee->user->password)->not->toBe('Str0ng-Passw0rd!')
            ->and(Hash::check('Str0ng-Passw0rd!', $employee->user->password))->toBeTrue();
    });

    test('stores an empty birthdate as null', function () {
        actingAsUser();

        $employee = app(CreateEmployee::class)->handle(employeeData(['birthdate' => '']));

        expect($employee->birthdate)->toBeNull();
    });

    test('rolls back the user when the employee cannot be created', function () {
        actingAsUser();

        expect(fn () => app(CreateEmployee::class)->handle(employeeData(['name' => str_repeat('a', 101)])))
            ->toThrow(QueryException::class);

        $this->assertDatabaseMissing('users', ['email' => 'ana@example.com']);
        $this->assertDatabaseEmpty('employees');
    });
});

describe('UpdateEmployee', function () {
    test('updates the employee and its user', function () {
        $admin = actingAsUser();
        $employee = createEmployee($admin->tenant);

        app(UpdateEmployee::class)->handle($employee, employeeData([
            'name' => 'Beatriz',
            'phone' => '1166660000',
            'email' => 'beatriz@example.com',
        ]));

        $this->assertDatabaseHas('employees', ['id' => $employee->id, 'name' => 'Beatriz', 'phone' => '1166660000']);
        $this->assertDatabaseHas('users', ['id' => $employee->user_id, 'name' => 'Beatriz', 'email' => 'beatriz@example.com']);
    });

    test('stores an empty birthdate as null', function () {
        $admin = actingAsUser();
        $employee = createEmployee($admin->tenant, ['birthdate' => '1990-05-12']);

        app(UpdateEmployee::class)->handle($employee, employeeData(['birthdate' => '']));

        $this->assertDatabaseHas('employees', ['id' => $employee->id, 'birthdate' => null]);
    });

    test('does not change the password', function () {
        $admin = actingAsUser();
        $employee = createEmployee($admin->tenant);
        $hash = $employee->user->password;

        app(UpdateEmployee::class)->handle($employee, employeeData());

        expect($employee->user->fresh()->password)->toBe($hash);
    });
});

describe('DeleteEmployee', function () {
    test('deletes the employee, its user and its schedules', function () {
        $admin = actingAsUser();
        $employee = createEmployee($admin->tenant);
        $employee->schedules()->create(['day_of_week' => 1, 'start_time' => '09:00', 'end_time' => '18:00']);

        app(DeleteEmployee::class)->handle($employee);

        $this->assertDatabaseMissing('employees', ['id' => $employee->id]);
        $this->assertDatabaseMissing('users', ['id' => $employee->user_id]);
        $this->assertDatabaseEmpty('employee_schedules');
    });

    test('does not touch other employees or users', function () {
        $admin = actingAsUser();
        $employee = createEmployee($admin->tenant);
        $other = createEmployee($admin->tenant);

        app(DeleteEmployee::class)->handle($employee);

        $this->assertModelExists($other);
        $this->assertModelExists($other->user);
        $this->assertModelExists($admin);
        expect(User::count())->toBe(2);
    });
});
