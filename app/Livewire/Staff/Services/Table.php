<?php

namespace App\Livewire\Staff\Services;

use App\Models\Service;
use Livewire\Attributes\On;
use Livewire\Component;

class Table extends Component
{
    public function render()
    {
        return view('livewire.staff.services.table', [
            'services' => Service::all(),
        ]);
    }

    #[On('service-created')]
    public function refreshServices() {}
}
