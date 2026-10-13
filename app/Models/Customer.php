<?php

namespace App\Models;

use Database\Factories\CustomerFactory;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['name', 'birthdate', 'phone', 'user_id'])]
class Customer extends Model
{
    /** @use HasFactory<CustomerFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'birthdate' => 'date:Y-m-d',
        ];
    }

    /**
     * Get the user that owns the customer.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the tenant that owns the customer.
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * The "booted" method of the model.
     * Creating a new customer will automatically set the tenant_id to the authenticated user's tenant_id.
     * Also, a global scope is added to ensure that only customers belonging to the authenticated user's tenant are retrieved.
     */
    protected static function booted(): void
    {
        static::creating(function (Customer $customer) {
            if (! auth()->check()) {
                throw new AuthenticationException('Necesitas estar autenticado para crear un cliente.');
            }
            $customer->tenant_id = auth()->user()->tenant_id;
        });

        static::addGlobalScope(function (Builder $builder) {
            if (! auth()->check()) {
                throw new AuthenticationException('Necesitas estar autenticado para acceder a los clientes.');
            }
            $builder->where('tenant_id', auth()->user()->tenant_id);
        });
    }
}
