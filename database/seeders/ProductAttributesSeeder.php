<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\AttributeValue;
use App\Models\ProductVariant;

class ProductAttributesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        $attributesData = [
            'By Pcs' => ['3 Pcs', '2 Pcs'],
            'Shirt' => ['Jacquard'],
            'Dupatta' => ['Chiffon'],
            'Color' => ['Teal', 'Red'],
            'Trouser' => ['Printed', 'Plain'],  
        ];

        
        $productAttributes = [];
        $attributeValues = [];

        
        foreach ($attributesData as $attributeName => $values) {
            $attribute = ProductAttribute::firstOrCreate(
                ['name' => $attributeName]
            );
            $productAttributes[$attributeName] = $attribute;

            foreach ($values as $value) {
                
                $colorCode = null;
                if ($attributeName === 'Color') {
                    switch ($value) {
                        case 'Teal':
                            $colorCode = '#008080';
                            break;
                        case 'Red':
                            $colorCode = '#FF0000';
                            break;
                        case 'Blue':
                            $colorCode = '#0000FF';
                            break;
                        
                    }
                }

                $attrValue = AttributeValue::firstOrCreate(
                    [
                        'product_attribute_id' => $attribute->id,
                        'value' => $value,
                    ],
                    [
                        'color_code' => $colorCode 
                    ]
                );
                $attributeValues[$attributeName][$value] = $attrValue;
            }
        }

        $this->command->info('Product Attributes and Values seeded successfully!');
 
        $product = Product::find(1); 
        if (!$product) {
            $this->command->error('Product with ID 1 not found. Please update the seeder with a valid product ID.');
            return;
        }
        
        $variant1 = ProductVariant::firstOrCreate(
            [
                'product_id' => $product->id,
                'sku' => $product->sku . '-TEAL-PRINTED', 
            ],
            [
                'price' => $product->price + 10.00, 
                'stock_level' => 50,
            ]
        );

        $variant1->attributeValues()->sync([
            $attributeValues['By Pcs']['3 Pcs']->id,
            $attributeValues['Shirt']['Jacquard']->id,
            $attributeValues['Dupatta']['Chiffon']->id,
            $attributeValues['Color']['Teal']->id,
            $attributeValues['Trouser']['Printed']->id,
        ]);
        $this->command->info('Variant 1 seeded for product ' . $product->name);


        $variant2 = ProductVariant::firstOrCreate(
            [
                'product_id' => $product->id,
                'sku' => $product->sku . '-RED-PLAIN', 
            ],
            [
                'price' => $product->price + 5.00, 
                'stock_level' => 30,
            ]
        );

        $variant2->attributeValues()->sync([
            $attributeValues['By Pcs']['3 Pcs']->id,
            $attributeValues['Shirt']['Jacquard']->id,
            $attributeValues['Dupatta']['Chiffon']->id,
            $attributeValues['Color']['Red']->id,
            $attributeValues['Trouser']['Plain']->id,
        ]);
        $this->command->info('Variant 2 seeded for product ' . $product->name);


        
    }
}