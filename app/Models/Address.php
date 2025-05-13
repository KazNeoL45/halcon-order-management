<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'street',
        'external_number',
        'city',
        'state',
        'zip_code',
        'country'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
