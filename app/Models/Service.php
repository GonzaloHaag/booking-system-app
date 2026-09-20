<?php

namespace App\Models;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Number;

#[Fillable(['name', 'description', 'price', 'duration_minutes', 'tenant_id'])]
class Service extends Model
{
    /**
     * The "booted" method of the model.
     * Creating a new customer will automatically set the tenant_id to the authenticated user's tenant_id.
     * Also, a global scope is added to ensure that only customers belonging to the authenticated user's tenant are retrieved.
     */
    protected static function booted(): void
    {
        static::creating(function (Service $service) {
            if (! auth()->check()) {
                throw new AuthenticationException('Necesitas estar autenticado para crear un servicio.');
            }
            $service->tenant_id = auth()->user()->tenant_id;
        });

        static::addGlobalScope(function (Builder $builder) {
            if (! auth()->check()) {
                throw new AuthenticationException('Necesitas estar autenticado para acceder a los servicios.');
            }
            $builder->where('tenant_id', auth()->user()->tenant_id);
        });
    }

    /**
     * Get the price of the service formatted as a currency string.
     */
    protected function formattedPrice(): Attribute
    {
        return Attribute::make(
            get: fn () => Number::currency($this->price), /** En el provider ya declare que moneda usar */
        );
    }
}
