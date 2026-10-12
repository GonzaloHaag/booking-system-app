<?php

namespace App\Livewire\Staff\Services;

use App\Actions\Service\UpdateService;
use App\Livewire\Forms\ServiceForm;
use App\Models\Service;
use Flux\Flux;
use Livewire\Attributes\Authorize;
use Livewire\Component;

class Update extends Component
{
    public ServiceForm $form;

    public Service $service;

    #[Authorize('update', 'service')]
    public function save(UpdateService $action)
    {
        $action->handle($this->service, $this->form->validate());
        $this->dispatch('service-updated');
        Flux::modal("update-service-{$this->service->id}")->close();
        Flux::toast(text: 'Servicio actualizado correctamente', variant: 'success');
    }

    public function mount(Service $service)
    {
        $this->service = $service;
        $this->form->setService($service);
    }

    public function render()
    {
        return view('livewire.staff.services.update');
    }
}
