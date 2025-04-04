<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
    ];

    public function roles(): BelongsToMany
    {

        return $this->belongsToMany(Role::class, 'client_role', 'client_id', 'role_id');
    }
}
