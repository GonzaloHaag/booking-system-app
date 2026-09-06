<?php

namespace App\Models;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'phone', 'birthdate', 'user_id', 'tenant_id'])]
class Employee extends Model
{
    /**
     * Get the user that owns the employee.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the tenant that owns the employee.
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Get the schedules for the employee.
     */
    public function schedules(): HasMany
    {
        return $this->hasMany(EmployeeSchedule::class);
    }

    /**
     * The "booted" method of the model.
     * Also, a global scope is added to ensure that only employees belonging to the authenticated user's tenant are retrieved.
     */
    protected static function booted(): void
    {
        /** Por ahora comento esto, le voy a asignar el tenan id del user creado
         * Luego vemos si hace falta aca.
         */
        // static::creating(function (Employee $employee) {
        //     if (! auth()->check()) {
        //         throw new AuthenticationException('Necesitas estar autenticado para crear un empleado.');
        //     }
        //     $employee->tenant_id = auth()->user()->tenant_id;
        // });

        static::addGlobalScope(function (Builder $builder) {
            if (! auth()->check()) {
                throw new AuthenticationException('Necesitas estar autenticado para acceder a los empleados.');
            }
            $builder->where('tenant_id', auth()->user()->tenant_id);
        });
    }
}
