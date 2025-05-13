<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'street',
        'external_number',
        'colony',
        'city',
        'state_id',
        'zip_code',
        'country_id',
    ];

    /**
     * Get the orders that use this address.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'address_id');
    }
    /**
     * Get the state for the address.
     */
    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }
    /**
     * Get the country for the address.
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }
}
