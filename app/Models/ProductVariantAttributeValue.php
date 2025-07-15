<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot; // Use Pivot for pivot models
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVariantAttributeValue extends Pivot
{
    use HasFactory;

    // Specify the table name as it's a custom pivot table name
    protected $table = 'product_variant_attribute_value';

    // Disable incrementing primary key since it's a composite key
    public $incrementing = false;

    // Define the fillable attributes for mass assignment
    protected $fillable = [
        'product_variant_id',
        'attribute_value_id',
    ];

    /**
     * Get the product variant that owns this pivot record.
     */
    public function productVariant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class);
    }

    /**
     * Get the attribute value that owns this pivot record.
     */
    public function attributeValue(): BelongsTo
    {
        return $this->belongsTo(AttributeValue::class);
    }
}
