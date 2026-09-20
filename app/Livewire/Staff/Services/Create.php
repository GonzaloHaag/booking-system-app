<?php

namespace App\Livewire\Staff\Services;

use App\Actions\Service\CreateService;
use App\Livewire\Forms\ServiceForm;
use Flux\Flux;
use Livewire\Component;

class Create extends Component
{
    public ServiceForm $form;

    public function save(CreateService $action)
    {
        $action->handle($this->form->validate());
        $this->dispatch('service-created');
        Flux::modal('create-service')->close();
        Flux::toast(text: 'Servicio creado exitosamente', variant: 'success');
        $this->form->reset();
    }

    public function render()
    {
        return view('livewire.staff.services.create');
    }
}
