<?php

namespace App\Livewire\Staff\Appointments;

use App\Models\Appointment;
use Illuminate\View\View;
use Livewire\Component;

class Table extends Component
{
    public function render(): View
    {
        return view('livewire.staff.appointments.table', [
            'appointments' => Appointment::with(['customer', 'service', 'employee'])->visibleTo(auth()->user())->orderBy('date')->orderBy('start_time')->get(),
        ]);
    }
}
