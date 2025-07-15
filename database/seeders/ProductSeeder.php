<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;    
use App\Models\Category;   
use App\Models\Inventory;  
use Illuminate\Support\Str; 

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        
        Product::truncate();
        Inventory::truncate();

        
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $categories = Category::all();

        if ($categories->isEmpty()) {
            $this->command->warn('No categories found. Please run CategorySeeder first.');
            return;
        }

        $baseProductNames = [
            "BNYTR-11 LUXURY LAWN 3 PC", "CZKLM-62 UNSTITCHED LAWN 3 PC", "DKLQP-87 PRINTED LAWN 3 PC",
            "EMTXJ-03 BASIC LAWN 3 PC", "FZLWA-49 UNSTITCHED LAWN 3 PC", "GHTQE-75 LUXURY LAWN 3 PC",
            "HNMKC-21 PRINTED LAWN 3 PC", "IQWUE-38 BASIC LAWN 3 PC", "JPLMD-96 UNSTITCHED LAWN 3 PC",
            "KXQZB-50 LUXURY LAWN 3 PC", "LRJVA-12 PRINTED LAWN 3 PC", "MXPTO-64 BASIC LAWN 3 PC",
            "NTYVB-29 UNSTITCHED LAWN 3 PC", "OUZKC-45 LUXURY LAWN 3 PC", "PQABM-07 BASIC LAWN 3 PC",
            "QZMWR-80 PRINTED LAWN 3 PC", "RNXTE-18 UNSTITCHED LAWN 3 PC", "SYLPA-34 LUXURY LAWN 3 PC",
            "TUBQJ-59 BASIC LAWN 3 PC", "UVLOX-72 PRINTED LAWN 3 PC"
        ];


        $productCounter = 0;

        foreach ($categories as $category) {
            $this->command->info("Seeding products for category: {$category->name}");

            
            for ($i = 0; $i < 6; $i++) {
                $productCounter++;
                $productName = $baseProductNames[array_rand($baseProductNames)]; 
                $productSlug = Str::slug($productName . '-' . Str::random(4)); 
                $productSku = 'SKU-' . Str::upper(Str::random(6)) . '-' . $productCounter; 

                
                $product = Product::create([
                    'name' => $productName,
                    'slug' => $productSlug,
                    'description' => "A high-quality and comfortable {$productName} for everyday wear. Made from premium materials.",
                    'image' => 'images/products/' . ($i % 5 + 1) . '.jpg', 
                    'price' => round(mt_rand(1500, 15000) / 100, 2), 
                    'sku' => $productSku,
                    'is_active' => true,
                    'category_id' => $category->id,
                    'stock_quantity' => mt_rand(10, 100), 
                ]);

                
                Inventory::create([
                    'product_id' => $product->id,
                    'stock_level' => $product->stock_quantity, 
                    'low_stock_threshold' => 10,
                ]);
            }
        }

        $this->command->info('Products and Inventory seeded successfully!');
    }
}
