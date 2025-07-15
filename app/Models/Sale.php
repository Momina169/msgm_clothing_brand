<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'quantity',
        'price_at_sale',
        'total_amount',
        'sale_date',
        'user_id',
        'guest_id',
        'notes',
    ];

    protected $casts = [
        'price_at_sale' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'sale_date' => 'datetime',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    
    public function guest(): BelongsTo
    {
        return $this->belongsTo(Guest::class);
    }

    // Automatically calculate total_amount before saving
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($sale) {
            $sale->total_amount = $sale->quantity * $sale->price_at_sale;
        });

        static::updating(function ($sale) {
            $sale->total_amount = $sale->quantity * $sale->price_at_sale;
        });
    }
}
