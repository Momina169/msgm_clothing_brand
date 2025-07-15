<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category; 

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Formal Wear',
                'slug' => 'formal-wear', 
                'description' => 'Explore the latest trends in formal eastern wear.',
                'image' => 'images/categories/formal_wear.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Party Wear',
                'slug' => 'party-wear',
                'description' => 'Discover stylish and elegant fashion for women.',
                'image' => 'images/categories/party_wear.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Wedding Wear',
                'slug' => 'wedding-wear',
                'description' => 'The luxury collection that make your look unique.',
                'image' => 'images/categories/wedding_wear.jpg',
                'is_active' => true,
            ],
            // spring/summer (parent)
            [
                'name' => 'Spring/Summer Collection',
                'slug' => 'spring-summer-collection',
                'description' => 'Light and vibrant styles for the warmer seasons.',
                'image' => 'images/categories/spring_summer_main.jpg', // Placeholder image
                'is_active' => true,
                'parent_id' => null,
            ],
        ];

        foreach ($categories as $categoryData) {
            Category::updateOrCreate(
                ['name' => $categoryData['name']],
                $categoryData // Pass the entire array, including the 'slug'
            );

        }
         $springSummerParent = Category::where('slug', 'spring-summer-collection')->first();

        if ($springSummerParent) {
            $subCategoriesToSeed = [
                [
                    'name' => 'Unstitched',
                    'slug' => 'unstitched',
                    'description' => 'Fabrics for custom tailoring.',
                    'image' => 'images/categories/unstitched.jpg',
                    'is_active' => true,
                    'parent_id' => $springSummerParent->id,
                ],
                [
                    'name' => 'Printed',
                    'slug' => 'printed',
                    'description' => 'Vibrant printed designs.',
                    'image' => 'images/categories/printed.jpg',
                    'is_active' => true,
                    'parent_id' => $springSummerParent->id,
                ],
                [
                    'name' => 'Festive',
                    'slug' => 'festive',
                    'description' => 'Special occasion and festive wear.',
                    'image' => 'images/categories/festive.jpg',
                    'is_active' => true,
                    'parent_id' => $springSummerParent->id,
                ],
                [
                    'name' => 'Ready to Wear - 2pc',
                    'slug' => 'ready-to-wear-2pc',
                    'description' => 'Coordinated two-piece outfits.',
                    'image' => 'images/categories/ready-to-wear-2pc.jpg',
                    'is_active' => true,
                    'parent_id' => $springSummerParent->id,
                ],
                [
                    'name' => 'Ready to Wear - 3pc',
                    'slug' => 'ready-to-wear-3pc',
                    'description' => 'Complete three-piece outfits.',
                    'image' => 'images/categories/ready-to-wear-3pc.jpg',
                    'is_active' => true,
                    'parent_id' => $springSummerParent->id,
                ],
                [
                    'name' => 'Essential Pret',
                    'slug' => 'essential-pret',
                    'description' => 'Everyday essential ready-to-wear.',
                    'image' => 'images/categories/pret.jpg',
                    'is_active' => true,
                    'parent_id' => $springSummerParent->id,
                ],
                
            ];

            foreach ($subCategoriesToSeed as $subCategoryData) {
                Category::updateOrCreate(
                    ['name' => $subCategoryData['name']],
                    $subCategoryData
                );
            }
            $this->command->info('Spring/Summer subcategories seeded successfully!');
        } else {
            $this->command->warn('Spring/Summer Collection parent category not found. Subcategories not seeded.');
        }

        $this->command->info('All categories seeded successfully!');

    }
}
