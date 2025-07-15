<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany; 

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'guest_id',
        'status', 
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function guest(): BelongsTo
    {
        return $this->belongsTo(Guest::class);
    }


    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    
    public function getTotalAmountAttribute(): float
    {
        return $this->cartItems->sum(function ($item) {
            return $item->quantity * $item->price;
        });
    }
}
