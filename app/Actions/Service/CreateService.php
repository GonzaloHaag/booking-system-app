<?php

namespace App\Actions\Service;

use App\Models\Service;

class CreateService
{
    /** @param array<string, mixed> $data */
    public function handle(array $data): Service
    {
        return Service::create([
            'name' => $data['name'],
            'description' => $data['description'] ?: null,
            'price' => $data['price'],
            'duration_minutes' => $data['duration_minutes'],
        ]);
    }
}
