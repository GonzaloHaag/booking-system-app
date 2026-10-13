<?php

use App\Actions\EmployeeSchedule\CreateOrUpdateEmployeeSchedule;
use App\Enums\UserRole;
use App\Livewire\Staff\Employees\Schedule;
use App\Models\Tenant;
use Livewire\Livewire;

function scheduleDays(array $overrides = []): array
{
    $days = [];

    foreach (range(1, 6) as $day) {
        $days[$day] = ['name' => "Día $day", 'start_time' => '09:00', 'end_time' => '18:00', 'enabled' => true];
    }

    foreach ($overrides as $day => $values) {
        $days[$day] = [...$days[$day], ...$values];
    }

    return $days;
}

describe('CreateOrUpdateEmployeeSchedule', function () {
    test('creates a row for every enabled day', function () {
        $admin = actingAsUser();
        $employee = createEmployee($admin->tenant);

        app(CreateOrUpdateEmployeeSchedule::class)->handle($employee, ['daysOfWeek' => scheduleDays([
            2 => ['enabled' => false],
            3 => ['enabled' => false],
            4 => ['enabled' => false],
            5 => ['enabled' => false],
            6 => ['enabled' => false],
        ])]);

        expect($employee->schedules()->pluck('day_of_week')->all())->toBe([1]);
    });

    test('updates an existing day instead of duplicating it', function () {
        $admin = actingAsUser();
        $employee = createEmployee($admin->tenant);
        $employee->schedules()->create(['day_of_week' => 1, 'start_time' => '09:00', 'end_time' => '18:00']);

        app(CreateOrUpdateEmployeeSchedule::class)->handle($employee, ['daysOfWeek' => scheduleDays([
            1 => ['start_time' => '10:00', 'end_time' => '14:00'],
        ])]);

        $monday = $employee->schedules()->where('day_of_week', 1)->get();

        expect($monday)->toHaveCount(1)
            ->and($monday->first()->start_time)->toBe('10:00')
            ->and($monday->first()->end_time)->toBe('14:00');
    });

    test('removes the row of a disabled day', function () {
        $admin = actingAsUser();
        $employee = createEmployee($admin->tenant);
        $employee->schedules()->create(['day_of_week' => 1, 'start_time' => '09:00', 'end_time' => '18:00']);

        app(CreateOrUpdateEmployeeSchedule::class)->handle($employee, ['daysOfWeek' => scheduleDays([
            1 => ['enabled' => false],
        ])]);

        expect($employee->schedules()->where('day_of_week', 1)->exists())->toBeFalse();
    });
});

describe('Schedule component', function () {
    test('loads the saved schedule and defaults for the other days', function () {
        $admin = actingAsUser();
        $employee = createEmployee($admin->tenant);
        $employee->schedules()->create(['day_of_week' => 1, 'start_time' => '10:00:00', 'end_time' => '14:00:00']);

        Livewire::test(Schedule::class, ['employee' => $employee])
            ->assertSet('form.daysOfWeek.1.enabled', true)
            ->assertSet('form.daysOfWeek.1.start_time', '10:00')
            ->assertSet('form.daysOfWeek.1.end_time', '14:00')
            ->assertSet('form.daysOfWeek.2.enabled', false)
            ->assertSet('form.daysOfWeek.2.start_time', '09:00');
    });

    test('saves the schedule', function () {
        $admin = actingAsUser();
        $employee = createEmployee($admin->tenant);

        Livewire::test(Schedule::class, ['employee' => $employee])
            ->set('form.daysOfWeek.1.enabled', true)
            ->set('form.daysOfWeek.1.start_time', '08:00')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('employee_schedules', [
            'employee_id' => $employee->id,
            'day_of_week' => 1,
            'start_time' => '08:00',
        ]);
    });

    test('validates the time format', function () {
        $admin = actingAsUser();
        $employee = createEmployee($admin->tenant);

        Livewire::test(Schedule::class, ['employee' => $employee])
            ->set('form.daysOfWeek.1.start_time', 'no-es-una-hora')
            ->call('save')
            ->assertHasErrors(['form.daysOfWeek.1.start_time' => 'date_format']);
    });

    test('forbids employees and customers', function (UserRole $role) {
        $user = actingAsUser($role);
        $employee = createEmployee($user->tenant);

        Livewire::test(Schedule::class, ['employee' => $employee])
            ->call('save')
            ->assertForbidden();

        $this->assertDatabaseEmpty('employee_schedules');
    })->with([
        'employee' => UserRole::EMPLOYEE,
        'customer' => UserRole::CUSTOMER,
    ]);

    test('forbids admins from another tenant', function () {
        actingAsUser();
        $foreign = createEmployee(Tenant::factory()->create());

        Livewire::test(Schedule::class, ['employee' => $foreign])
            ->call('save')
            ->assertForbidden();

        $this->assertDatabaseEmpty('employee_schedules');
    });
});
