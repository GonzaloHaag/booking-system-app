<?php

use App\Enums\UserRole;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Service;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "pest()" function to bind different classes or traits.
|
*/

pest()->extend(TestCase::class)
    ->use(RefreshDatabase::class)
    ->in('Feature');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

/**
 * Crea un usuario sin pasar por los eventos del modelo.
 *
 * El hook `creating` de User exige un usuario autenticado y le asigna su tenant_id,
 * pero en un test aún no hay nadie autenticado (o se necesita un tenant distinto).
 * Muteamos los eventos solo acá, en vez de aflojar el hook del modelo.
 *
 * @param  array<string, mixed>  $attributes
 */
function createUser(array $attributes = [], ?Tenant $tenant = null): User
{
    return User::withoutEvents(
        fn () => User::factory()
            ->for($tenant ?? Tenant::factory()->create())
            ->create($attributes)
    );
}

/**
 * Crea un usuario con el rol indicado y lo autentica.
 *
 * @param  array<string, mixed>  $attributes
 */
function actingAsUser(UserRole $role = UserRole::ADMIN, ?Tenant $tenant = null, array $attributes = []): User
{
    $user = createUser([...$attributes, 'role' => $role], $tenant);

    test()->actingAs($user);

    return $user;
}

/**
 * Crea un servicio de un tenant sin pasar por los eventos del modelo.
 *
 * @param  array<string, mixed>  $attributes
 */
function createService(Tenant $tenant, array $attributes = []): Service
{
    return Service::withoutEvents(
        fn () => Service::factory()->create([...$attributes, 'tenant_id' => $tenant->id])
    );
}

/**
 * Crea un cliente de un tenant sin pasar por los eventos del modelo.
 *
 * @param  array<string, mixed>  $attributes
 */
function createCustomer(Tenant $tenant, array $attributes = []): Customer
{
    return Customer::withoutEvents(
        fn () => Customer::factory()->for($tenant)->create($attributes)
    );
}

/**
 * Crea un empleado de un tenant, junto con su usuario, sin pasar por los eventos de los modelos.
 *
 * @param  array<string, mixed>  $attributes
 */
function createEmployee(Tenant $tenant, array $attributes = []): Employee
{
    $user = createUser(['role' => UserRole::EMPLOYEE], $tenant);

    return Employee::withoutEvents(
        fn () => Employee::factory()->for($tenant)->create([...$attributes, 'user_id' => $user->id])
    );
}
