<?php
// database/seeders/VariationSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductVariation;

class VariationSeeder extends Seeder
{
    public function run(): void
    {
        $variations = [
            // Sizes
            [
                'variation_code' => 'SIZE-S',
                'variation_name' => 'Small',
                'variation_type' => 'size',
                'value' => 'S',
                'status' => 'active'
            ],
            [
                'variation_code' => 'SIZE-M',
                'variation_name' => 'Medium',
                'variation_type' => 'size',
                'value' => 'M',
                'status' => 'active'
            ],
            [
                'variation_code' => 'SIZE-L',
                'variation_name' => 'Large',
                'variation_type' => 'size',
                'value' => 'L',
                'status' => 'active'
            ],
            [
                'variation_code' => 'SIZE-XL',
                'variation_name' => 'Extra Large',
                'variation_type' => 'size',
                'value' => 'XL',
                'status' => 'active'
            ],
            [
                'variation_code' => 'SIZE-XXL',
                'variation_name' => 'Double Extra Large',
                'variation_type' => 'size',
                'value' => 'XXL',
                'status' => 'active'
            ],
            
            // Colors
            [
                'variation_code' => 'COL-RED',
                'variation_name' => 'Red',
                'variation_type' => 'color',
                'value' => '#FF0000',
                'status' => 'active'
            ],
            [
                'variation_code' => 'COL-BLUE',
                'variation_name' => 'Blue',
                'variation_type' => 'color',
                'value' => '#0000FF',
                'status' => 'active'
            ],
            [
                'variation_code' => 'COL-GREEN',
                'variation_name' => 'Green',
                'variation_type' => 'color',
                'value' => '#00FF00',
                'status' => 'active'
            ],
            [
                'variation_code' => 'COL-BLACK',
                'variation_name' => 'Black',
                'variation_type' => 'color',
                'value' => '#000000',
                'status' => 'active'
            ],
            [
                'variation_code' => 'COL-WHITE',
                'variation_name' => 'White',
                'variation_type' => 'color',
                'value' => '#FFFFFF',
                'status' => 'active'
            ],
            
            // Flavors
            [
                'variation_code' => 'FLAV-BEEF',
                'variation_name' => 'Beef',
                'variation_type' => 'flavor',
                'value' => 'Beef',
                'status' => 'active'
            ],
            [
                'variation_code' => 'FLAV-CHICKEN',
                'variation_name' => 'Chicken',
                'variation_type' => 'flavor',
                'value' => 'Chicken',
                'status' => 'active'
            ],
            [
                'variation_code' => 'FLAV-PORK',
                'variation_name' => 'Pork',
                'variation_type' => 'flavor',
                'value' => 'Pork',
                'status' => 'active'
            ],
            [
                'variation_code' => 'FLAV-SPICY',
                'variation_name' => 'Spicy',
                'variation_type' => 'flavor',
                'value' => 'Spicy',
                'status' => 'active'
            ],
            [
                'variation_code' => 'FLAV-ORIGINAL',
                'variation_name' => 'Original',
                'variation_type' => 'flavor',
                'value' => 'Original',
                'status' => 'active'
            ],
            
            // Styles
            [
                'variation_code' => 'STYLE-CLASSIC',
                'variation_name' => 'Classic',
                'variation_type' => 'style',
                'value' => 'Classic',
                'status' => 'active'
            ],
            [
                'variation_code' => 'STYLE-MODERN',
                'variation_name' => 'Modern',
                'variation_type' => 'style',
                'value' => 'Modern',
                'status' => 'active'
            ],
            [
                'variation_code' => 'STYLE-VINTAGE',
                'variation_name' => 'Vintage',
                'variation_type' => 'style',
                'value' => 'Vintage',
                'status' => 'active'
            ],
        ];

        foreach ($variations as $variation) {
            ProductVariation::create($variation);
        }

        $this->command->info('Variations seeded successfully!');
    }
}