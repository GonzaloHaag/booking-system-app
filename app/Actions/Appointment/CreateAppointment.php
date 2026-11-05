<?php

namespace App\Actions\Appointment;

use App\Enums\AppointmentStatus;
use App\Exceptions\InvalidAppointmentTimeException;
use App\Models\Appointment;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Service;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;

class CreateAppointment
{
    public function handle(Employee $employee, Service $service, Customer $customer, CarbonImmutable $startsAt, ?string $notes = null): Appointment
    {
        if ($startsAt->isPast()) {
            throw new InvalidAppointmentTimeException('La fecha de inicio no puede ser en el pasado');
        }
        $endsAt = $startsAt->addMinutes($service->duration_minutes);
        $appointment = DB::transaction(function () use ($employee, $service, $customer, $startsAt, $endsAt, $notes): Appointment {
            return Appointment::create([
                'status' => AppointmentStatus::PENDING,
                'date' => $startsAt->toDateString(),
                'start_time' => $startsAt->format('H:i'),
                'end_time' => $endsAt->format('H:i'),
                'employee_id' => $employee->id,
                'customer_id' => $customer->id,
                'service_id' => $service->id,
                'notes' => $notes ?: null,
            ]);
        });

        return $appointment;
    }
}
