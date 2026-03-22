<?php
// database/seeders/StockSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Stock;
use App\Models\Product;

class StockSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::all();
        
        foreach ($products as $product) {
            Stock::updateOrCreate(
                ['product_id' => $product->id],
                [
                    'quantity' => rand(10, 200),
                    'min_quantity' => $product->reorder_level ?? 10,
                    'max_quantity' => $product->max_level ?? 500,
                    'location' => chr(rand(65, 90)) . rand(1, 10),
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            );
        }

        $this->command->info('Stocks seeded successfully!');
    }
}