<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'image',
        'name',
        'slug',
        'description',
        'price',
        'sku',
        'is_active',
        'category_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'price' => 'decimal:2',
    ];

    public function images()
    {
    return $this->hasMany(ProductImage::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function inventory(): HasOne
    {
        return $this->hasOne(Inventory::class);
    }

    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    // public function wishlists(): HasMany
    // {
    //     return $this->hasMany(Wishlist::class);
    // }

     public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

        public function attributeValues(): BelongsToMany
    {
        return $this->belongsToMany(AttributeValue::class, 'product_attribute_values');
    }

    public function productAttributes(): BelongsToMany
    {
        return $this->belongsToMany(ProductAttribute::class, 'attribute_values', 'product_attribute_id', 'id')
                    ->using(ProductAttributeValue::class) //  make a custom pivot model
                    ->wherePivot('product_id', $this->id); // Filter by this product's ID
    }
}