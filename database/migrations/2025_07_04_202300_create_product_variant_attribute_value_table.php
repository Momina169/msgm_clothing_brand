<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product_variant_attribute_value', function (Blueprint $table) {
            
             // Foreign key to product_variants table
            $table->foreignId('product_variant_id')->constrained('product_variants')->onDelete('cascade');

            // Foreign key to attribute_values table
            $table->foreignId('attribute_value_id')->constrained('attribute_values')->onDelete('cascade');

            // Set a composite primary key to ensure uniqueness of each combination
            $table->primary(['product_variant_id', 'attribute_value_id'], 'pvav_primary');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variant_attribute_values');
    }
};
