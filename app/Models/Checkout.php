<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Checkout extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'guest_email',
        'address_id',
        'shipping_id',
        'total_amount',
        'status',
        'payment_method',
        'transaction_id',
        'notes',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function shipping()
    {
        return $this->belongsTo(ShippingMethod::class);
    }
}