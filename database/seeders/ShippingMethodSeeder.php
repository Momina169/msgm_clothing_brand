<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ShippingMethod; // Make sure to import your ShippingMethod model
use Illuminate\Support\Facades\DB; // Import the DB facade

class ShippingMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable foreign key checks temporarily
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Clear existing shipping methods to prevent duplicates on re-seeding
        ShippingMethod::truncate();

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Seed basic shipping methods
        ShippingMethod::create([
            'name' => 'Standard Shipping',
            'description' => 'Delivery within 5-7 business days.',
            'cost' => 5.00,
            'is_active' => true,
        ]);

        ShippingMethod::create([
            'name' => 'Express Shipping',
            'description' => 'Delivery within 1-2 business days.',
            'cost' => 15.00,
            'is_active' => true,
        ]);

        ShippingMethod::create([
            'name' => 'Local Pickup',
            'description' => 'Pick up your order from our local store.',
            'cost' => 0.00,
            'is_active' => true,
        ]);

    }
}

