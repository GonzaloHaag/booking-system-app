<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['day_of_week', 'start_time', 'end_time', 'employee_id'])]
class EmployeeSchedule extends Model
{
    /**
     * Get the employee that owns the schedule.
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Get the start time of the schedule in HH:MM format.
     */
    protected function startTime(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => substr($value, 0, 5), // Retorna solo la hora y minutos (HH:MM)
        );
    }

    /**
     * Get the end time of the schedule in HH:MM format.
     */
    protected function endTime(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => substr($value, 0, 5), // Retorna solo la hora y minutos (HH:MM)
        );
    }
}
