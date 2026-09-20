<?php

namespace App\Actions\EmployeeSchedule;

use App\Models\Employee;

class CreateOrUpdateEmployeeSchedule
{
    /** @param array<string, mixed> $data */
    public function handle(Employee $employee, array $data): void
    {
        foreach ($data['daysOfWeek'] as $dayOfWeek => $schedule) {
            if ($schedule['enabled']) {
                $employee->schedules()->updateOrCreate(
                    ['day_of_week' => $dayOfWeek],
                    [
                        'start_time' => $schedule['start_time'],
                        'end_time' => $schedule['end_time'],
                    ]
                );
            } else {
                $employee->schedules()->where('day_of_week', $dayOfWeek)->delete();
            }
        }
    }
}
