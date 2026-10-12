<?php

namespace App\Actions\Service;

use App\Models\Service;

class DeleteService
{
    public function handle(Service $service): Service
    {
        $service->delete();

        return $service;
    }
}
