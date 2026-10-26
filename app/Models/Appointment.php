<?php

namespace App\Models;

use App\Enums\AppointmentStatus;
use Database\Factories\AppointmentFactory;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['status', 'date', 'start_time', 'end_time', 'tenant_id', 'employee_id', 'customer_id', 'service_id'])]
class Appointment extends Model
{
    /** @use HasFactory<AppointmentFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => AppointmentStatus::class,
            'date' => 'date:Y-m-d',
        ];
    }

    /**
     * Get the tenant that owns the appointment.
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Get the employee that owns the appointment.
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class)->withTrashed();
    }

    /**
     * Get the customer that owns the appointment.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the service that owns the appointment.
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class)->withTrashed();
    }

    /**
     * The "booted" method of the model.
     * Creating a new appointment will automatically set the tenant_id to the authenticated user's tenant_id.
     * Also, a global scope is added to ensure that only appointments belonging to the authenticated user's tenant are retrieved.
     */
    protected static function booted(): void
    {
        static::creating(function (Appointment $appointment) {
            if (! auth()->check()) {
                throw new AuthenticationException('Necesitas estar autenticado para crear un turno.');
            }
            $appointment->tenant_id = auth()->user()->tenant_id;
        });

        static::addGlobalScope(function (Builder $builder) {
            if (! auth()->check()) {
                throw new AuthenticationException('Necesitas estar autenticado para acceder a los turnos.');
            }
            $builder->where('tenant_id', auth()->user()->tenant_id);
        });
    }
}
