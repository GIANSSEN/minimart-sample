<?php
// app/Console/Commands/FixStockRecords.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use App\Models\Stock;
use Illuminate\Database\Eloquent\Collection;

class FixStockRecords extends Command
{
    protected $signature = 'stock:fix';
    protected $description = 'Fix stock records for all products';

    public function handle()
    {
        $this->info('====================================');
        $this->info('FIXING STOCK RECORDS');
        $this->info('====================================');
        
        // Get all products with their stock relationship
        /** @var \Illuminate\Database\Eloquent\Collection|\App\Models\Product[] $allProducts */
        $allProducts = Product::with('stock')->get();
        
        /** @var \Illuminate\Database\Eloquent\Collection|\App\Models\Product[] $productsWithoutStock */
        $productsWithoutStock = $allProducts->filter(function($product) {
            return !$product->stock;
        });
        
        $count = $productsWithoutStock->count();
        
        $this->info("Found {$count} products without stock records.");
        
        if ($count === 0) {
            $this->info('✅ All products already have stock records!');
        } else {
            $this->info('Creating stock records...');
            $bar = $this->output->createProgressBar($count);
            
            foreach ($productsWithoutStock as $product) {
                /** @var \App\Models\Product $product */
                Stock::create([
                    'product_id' => $product->id,
                    'quantity' => 0,
                    'min_quantity' => $product->reorder_level ?? 10,
                    'max_quantity' => $product->max_level ?? 1000,
                    'location' => $product->shelf_location ?? 'A1',
                ]);
                
                $bar->advance();
            }
            
            $bar->finish();
            $this->newLine();
            $this->info('✅ Stock records created successfully!');
        }
        
        // Update inventory status for ALL products
        $this->info('Updating inventory status for all products...');
        
        /** @var \Illuminate\Database\Eloquent\Collection|\App\Models\Product[] $products */
        $products = Product::with('stock')->get();
        $bar = $this->output->createProgressBar($products->count());
        
        $updated = 0;
        foreach ($products as $product) {
            /** @var \App\Models\Product $product */
            try {
                if (method_exists($product, 'updateInventoryStatus')) {
                    $product->updateInventoryStatus();
                    $updated++;
                } else {
                    $this->warn("Product ID {$product->id} does not have updateInventoryStatus method");
                }
            } catch (\Exception $e) {
                $this->warn("Failed to update product ID {$product->id}: {$e->getMessage()}");
            }
            $bar->advance();
        }
        
        $bar->finish();
        $this->newLine();
        $this->info("✅ Updated inventory status for {$updated} products!");
        
        // Verify the fix
        $remaining = Product::doesntHave('stock')->count();
        if ($remaining === 0) {
            $this->info('✅ All products now have stock records!');
        } else {
            $this->warn("⚠️  {$remaining} products still don't have stock records.");
        }
        
        // Show summary
        $this->showSummary();
        
        return 0;
    }

    /**
     * Show stock summary
     */
    private function showSummary()
    {
        $this->info('====================================');
        $this->info('STOCK SUMMARY');
        $this->info('====================================');
        
        $totalProducts = Product::count();
        $withStock = Stock::count();
        $totalStock = Stock::sum('quantity');
        
        // Calculate stock status counts
        $inStock = 0;
        $lowStock = 0;
        $outOfStock = 0;
        
        /** @var \Illuminate\Database\Eloquent\Collection|\App\Models\Product[] $products */
        $products = Product::with('stock')->get();
        foreach ($products as $product) {
            /** @var \App\Models\Product $product */
            if (!$product->stock) {
                $outOfStock++;
            } elseif ($product->stock->quantity <= 0) {
                $outOfStock++;
            } elseif ($product->stock->quantity <= $product->reorder_level) {
                $lowStock++;
            } else {
                $inStock++;
            }
        }
        
        $this->table(
            ['Metric', 'Count'],
            [
                ['Total Products', $totalProducts],
                ['Products with Stock', $withStock],
                ['Total Stock Quantity', $totalStock],
                ['In Stock', $inStock],
                ['Low Stock', $lowStock],
                ['Out of Stock', $outOfStock],
            ]
        );
        
        $this->info('====================================');
    }
}