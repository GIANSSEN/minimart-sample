<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            [
                'category_name' => 'Beverages',
                'description' => 'Soft drinks, juices, water, and other drinks',
                'status' => 'active',
                'created_by' => 1
            ],
            [
                'category_name' => 'Snacks',
                'description' => 'Chips, crackers, and snack foods',
                'status' => 'active',
                'created_by' => 1
            ],
            [
                'category_name' => 'Canned Goods',
                'description' => 'Canned meats, vegetables, and fruits',
                'status' => 'active',
                'created_by' => 1
            ],
            [
                'category_name' => 'Noodles',
                'description' => 'Instant noodles and pasta',
                'status' => 'active',
                'created_by' => 1
            ],
            [
                'category_name' => 'Household',
                'description' => 'Cleaning and household items',
                'status' => 'active',
                'created_by' => 1
            ],
            [
                'category_name' => 'Personal Care',
                'description' => 'Toiletries and personal care items',
                'status' => 'active',
                'created_by' => 1
            ],
            [
                'category_name' => 'Frozen Goods',
                'description' => 'Meats, processed foods, and other frozen items',
                'status' => 'active',
                'created_by' => 1
            ],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['category_name' => $category['category_name']],
                $category
            );
        }
    }
}