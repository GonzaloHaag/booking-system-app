<?php

namespace App\Actions\Service;

use App\Models\Service;

class UpdateService
{
    /** @param array<string, mixed> $data */
    public function handle(Service $service, array $data): Service
    {
        $service->update([
            'name' => $data['name'],
            'description' => $data['description'] ?: null,
            'price' => $data['price'],
            'duration_minutes' => $data['duration_minutes'],
        ]);

        return $service;
    }
}
