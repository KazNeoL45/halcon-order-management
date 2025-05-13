<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne; 
use Illuminate\Database\Eloquent\SoftDeletes;    

class Order extends Model
{
    /** @use HasFactory<\Database\Factories\OrderFactory> */
    use HasFactory;
    use SoftDeletes; 

    protected $fillable = [
        'client_id',
        'invoice_number',
        'invoice_date',
        'status',
        'total',
        'delivery_address',
        'address_id', 
        'notes',
    ];

    /**
     * Default attribute values.
     *
     * @var array
     */
    protected $attributes = [
        'status' => 'ordered', 
    ];

    /**
     * The attributes that should be cast.
     * Using $casts is preferred for Laravel 9+.
     * If you are using an older version of Laravel (before 9),
     * you might prefer to use: protected $dates = ['invoice_date', 'deleted_at'];
     *
     * @var array<string, string>
     */
    protected $casts = [
        'invoice_date' => 'datetime', 
        'deleted_at' => 'datetime',   
    ];

    /**
     * Get the client that owns the order.
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Get the items for the order.
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get the payment associated with the order.
     * Corrected return type hint to HasOne to match the ->hasOne() Eloquent method.
     */
    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }
}