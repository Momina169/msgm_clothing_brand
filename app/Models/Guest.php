<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Guest extends Model
{
    use HasFactory;

    protected $fillable = [
        'guest_id',
    ];

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'guest_id', 'guest_id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'guest_id', 'guest_id');
    }

    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class, 'guest_id', 'guest_id');
    }
}