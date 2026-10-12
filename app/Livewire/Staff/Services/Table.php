<?php

namespace App\Livewire\Staff\Services;

use App\Actions\Service\DeleteService;
use App\Models\Service;
use Flux\Flux;
use Livewire\Attributes\On;
use Livewire\Component;

class Table extends Component
{
    public function delete(DeleteService $action, int $id)
    {
        $service = Service::findOrFail($id); /** Esto pasa por el global scope que defini en el modelo */
        $this->authorize('delete', $service); /** Policy */
        $action->handle($service);
        Flux::toast(text: "Servicio {$service->name} eliminado correctamente", variant: 'success');
        Flux::modal("delete-service-{$id}")->close();
    }

    public function render()
    {
        return view('livewire.staff.services.table', [
            'services' => Service::all(),
        ]);
    }

    #[On('service-created')]
    #[On('service-updated')]
    public function refreshServices() {}
}
