<?php

use App\Actions\Customer\CreateCustomerAccount;
use App\Enums\UserRole;
use App\Livewire\Staff\Customers\CreateAccount;
use App\Livewire\Staff\Customers\Table;
use App\Models\Tenant;
use App\Models\User;
use Livewire\Livewire;

function accountData(array $overrides = []): array
{
    return [
        'name' => 'Ana',
        'email' => 'ana@example.com',
        'password' => 'Str0ng-Passw0rd!',
        'password_confirmation' => 'Str0ng-Passw0rd!',
        ...$overrides,
    ];
}

describe('CreateCustomerAccount action', function () {
    test('creates a customer user in the same tenant and links it', function () {
        $staff = actingAsUser();
        $customer = createCustomer($staff->tenant);

        app(CreateCustomerAccount::class)->handle($customer, accountData());

        $user = User::where('email', 'ana@example.com')->first();

        expect($user->role)->toBe(UserRole::CUSTOMER)
            ->and($user->tenant_id)->toBe($staff->tenant_id)
            ->and($customer->refresh()->user_id)->toBe($user->id);
    });

    test('hashes the password', function () {
        $staff = actingAsUser();
        $customer = createCustomer($staff->tenant);

        app(CreateCustomerAccount::class)->handle($customer, accountData());

        $user = User::where('email', 'ana@example.com')->first();

        expect($user->password)->not->toBe('Str0ng-Passw0rd!')
            ->and(Hash::check('Str0ng-Passw0rd!', $user->password))->toBeTrue();
    });

    test('does not create a second account for a customer that already has one', function () {
        $staff = actingAsUser();
        $customer = createCustomer($staff->tenant);
        $action = app(CreateCustomerAccount::class);
        $action->handle($customer, accountData());

        expect(fn () => $action->handle($customer->refresh(), accountData(['email' => 'otra@example.com'])))
            ->toThrow(Exception::class);

        $this->assertDatabaseMissing('users', ['email' => 'otra@example.com']);
    });
});

describe('CreateAccount component', function () {
    test('creates the account and dispatches the event', function () {
        $staff = actingAsUser();
        $customer = createCustomer($staff->tenant);

        Livewire::test(CreateAccount::class, ['customer' => $customer])
            ->set('form.email', 'ana@example.com')
            ->set('form.password', 'Str0ng-Passw0rd!')
            ->set('form.password_confirmation', 'Str0ng-Passw0rd!')
            ->call('save')
            ->assertHasNoErrors()
            ->assertDispatched('customer-account-created');

        $this->assertDatabaseHas('users', ['email' => 'ana@example.com', 'tenant_id' => $staff->tenant_id]);
    });

    test('validates the required fields', function () {
        $staff = actingAsUser();
        $customer = createCustomer($staff->tenant);

        Livewire::test(CreateAccount::class, ['customer' => $customer])
            ->call('save')
            ->assertHasErrors([
                'form.email' => 'required',
                'form.password' => 'required',
            ]);
    });

    test('validates the password confirmation', function () {
        $staff = actingAsUser();
        $customer = createCustomer($staff->tenant);

        Livewire::test(CreateAccount::class, ['customer' => $customer])
            ->set('form.email', 'ana@example.com')
            ->set('form.password', 'Str0ng-Passw0rd!')
            ->set('form.password_confirmation', 'otra-cosa')
            ->call('save')
            ->assertHasErrors(['form.password' => 'confirmed']);
    });

    test('rejects an email already used in the same tenant', function () {
        $staff = actingAsUser();
        createUser(['email' => 'ana@example.com'], $staff->tenant);
        $customer = createCustomer($staff->tenant);

        Livewire::test(CreateAccount::class, ['customer' => $customer])
            ->set('form.email', 'ana@example.com')
            ->set('form.password', 'Str0ng-Passw0rd!')
            ->set('form.password_confirmation', 'Str0ng-Passw0rd!')
            ->call('save')
            ->assertHasErrors(['form.email' => 'unique']);
    });

    test('accepts an email used in another tenant', function () {
        $staff = actingAsUser();
        createUser(['email' => 'ana@example.com'], Tenant::factory()->create());
        $customer = createCustomer($staff->tenant);

        Livewire::test(CreateAccount::class, ['customer' => $customer])
            ->set('form.email', 'ana@example.com')
            ->set('form.password', 'Str0ng-Passw0rd!')
            ->set('form.password_confirmation', 'Str0ng-Passw0rd!')
            ->call('save')
            ->assertHasNoErrors();
    });

    test('forbids customers', function () {
        $user = actingAsUser(UserRole::CUSTOMER);
        $customer = createCustomer($user->tenant);

        Livewire::test(CreateAccount::class, ['customer' => $customer])
            ->set('form.email', 'ana@example.com')
            ->set('form.password', 'Str0ng-Passw0rd!')
            ->set('form.password_confirmation', 'Str0ng-Passw0rd!')
            ->call('save')
            ->assertForbidden();

        $this->assertDatabaseMissing('users', ['email' => 'ana@example.com']);
    });

    test('forbids staff from another tenant', function () {
        actingAsUser();
        $foreign = createCustomer(Tenant::factory()->create());

        Livewire::test(CreateAccount::class, ['customer' => $foreign])
            ->set('form.email', 'ana@example.com')
            ->set('form.password', 'Str0ng-Passw0rd!')
            ->set('form.password_confirmation', 'Str0ng-Passw0rd!')
            ->call('save')
            ->assertForbidden();

        $this->assertDatabaseMissing('users', ['email' => 'ana@example.com']);
        $this->assertDatabaseHas('customers', ['id' => $foreign->id, 'user_id' => null]);
    });
});

test('the table only offers to create an account when the customer has none', function () {
    $staff = actingAsUser();
    $withAccount = createCustomer($staff->tenant, ['name' => 'Con cuenta', 'user_id' => createUser(['email' => 'c@example.com'], $staff->tenant)->id]);
    $withoutAccount = createCustomer($staff->tenant, ['name' => 'Sin cuenta propia']);

    Livewire::test(Table::class)
        ->assertSee('c@example.com')
        ->assertSeeHtml('create-customer-account-'.$withoutAccount->id)
        ->assertDontSeeHtml('create-customer-account-'.$withAccount->id);
});
