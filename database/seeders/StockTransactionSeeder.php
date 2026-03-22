<?php
// database/seeders/StockTransactionSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StockTransaction;
use App\Models\Product;
use App\Models\User;
use App\Models\Stock;

class StockTransactionSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::all();
        $users = User::all();
        
        foreach ($products->take(10) as $product) {
            $stock = Stock::where('product_id', $product->id)->first();
            
            if ($stock) {
                // Initial stock in
                StockTransaction::create([
                    'stock_id' => $stock->id,
                    'product_id' => $product->id,
                    'user_id' => $users->random()->id,
                    'type' => 'in',
                    'quantity' => $stock->quantity,
                    'previous_quantity' => 0,
                    'new_quantity' => $stock->quantity,
                    'reason' => 'Initial stock',
                    'notes' => 'Initial inventory setup',
                    'created_at' => now()->subDays(rand(1, 30))
                ]);
                
                // Random stock out
                if ($stock->quantity > 20) {
                    $outQty = rand(5, 15);
                    StockTransaction::create([
                        'stock_id' => $stock->id,
                        'product_id' => $product->id,
                        'user_id' => $users->random()->id,
                        'type' => 'out',
                        'quantity' => $outQty,
                        'previous_quantity' => $stock->quantity,
                        'new_quantity' => $stock->quantity - $outQty,
                        'reason' => 'Sold to customer',
                        'notes' => 'POS transaction',
                        'created_at' => now()->subDays(rand(1, 15))
                    ]);
                }
            }
        }

        $this->command->info('Stock transactions seeded successfully!');
    }
}