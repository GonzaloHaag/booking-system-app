<?php

namespace App\Policies;

use App\Models\Appointment;
use App\Models\Employee;
use App\Models\User;

class AppointmentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Appointment $appointment): bool
    {
        if ($user->isAdmin()) {
            return $user->tenant_id === $appointment->tenant_id;
        }

        if ($user->isEmployee()) {
            return $user->tenant_id === $appointment->tenant_id
                && $user->id === $appointment->employee->user_id;
        }

        return $user->id === $appointment->customer?->user_id;
    }

    /**
     * Determine whether the user can create models.
     * Una forma de leerlo: isAdmin() con tenant, o isStaff() con dueño del turno, o customer con dueño.
     * 1. Si el empleado es de otro tenant, devolvé false. Comparás $user->tenant_id con $employee->tenant_id.
     *2. Si el usuario que reserva es un employee, solo puede reservar para sí mismo. Comparás $employee->user_id con $user->id. Para saber si es employee, usá $user->hasRole(UserRole::EMPLOYEE).
     *3. En cualquier otro caso, devolvé true (admin y customer)
     */
    public function create(User $user, Employee $employee): bool
    {
        if ($user->tenant_id !== $employee->tenant_id) {
            return false;
        }
        if ($user->isEmployee() && $employee->user_id !== $user->id) {
            return false;
        }

        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Appointment $appointment): bool
    {
        if ($user->isAdmin()) {
            return $user->tenant_id === $appointment->tenant_id;
        }

        if ($user->isEmployee()) {
            return $user->tenant_id === $appointment->tenant_id
                && $user->id === $appointment->employee->user_id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Appointment $appointment): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Appointment $appointment): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Appointment $appointment): bool
    {
        return false;
    }

    /**
     * Determine whether the user can cancel the model.
     */
    public function cancel(User $user, Appointment $appointment): bool
    {
        if ($user->isAdmin()) {
            return $user->tenant_id === $appointment->tenant_id;
        }

        if ($user->isEmployee()) {
            return $user->tenant_id === $appointment->tenant_id
                && $user->id === $appointment->employee->user_id;
        }

        return $user->id === $appointment->customer?->user_id;
    }
}
